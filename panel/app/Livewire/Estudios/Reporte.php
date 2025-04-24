<?php

namespace App\Livewire\Estudios;

use App\Jobs\ProcesarConsultaReportes;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class Reporte extends Component
{
    public $informacion;

    public $tipoReporte="0";
    public $resultadoObtenido=false;
    public $resultado;

    //Variables del registro
    public $fechaInicio;
    public $fechaFin;
    public $fechaHoy;

    public $estudiosSeleccionados=[];
    public $indexSeleccionados=[];
    public $estudioActual="0";

    public $ejecutandoReporte=false;
    public $reporteListo=false;

    protected $listeners = ['progressDone'];

    public $accionesInteres=["CUMTEST","MOVTEST","CONTROL","MOV","CUM","SCUM","XCUM"];

    public function mount($information){
        $this->informacion=$information;

        usort($this->informacion, function ($a, $b) {
            return strcmp($a["StudyName"], $b["StudyName"]);
        });

        $this->fechaHoy=Carbon::today()->toDateString();
    }

    public function adicionarEstudio(){
        //Analizo que no sea cero o que ya esté
        if($this->estudioActual<=0 | in_array($this->estudioActual-1,$this->indexSeleccionados) | $this->estudioActual>count($this->informacion)){
            $this->dispatch('mostrarToast', 'Continuar reporte', "El estudio no es válido, está vacío o ya fue añadido");
            return;
        }

        $this->indexSeleccionados[]=$this->estudioActual-1;
        $this->estudioActual="0";
    }

    public function quitarEstudio($index){
        // Eliminar el índice del array
        unset($this->indexSeleccionados[$index]);

        // Reindexar el array para reorganizar los índices
        $this->indexSeleccionados = array_values($this->indexSeleccionados);
    }

    public function completarReporte(){
        //Valido las fechas
        if (!$this->fechaInicio || !strtotime($this->fechaInicio)) {
            $this->dispatch('mostrarToast', 'Continuar reporte', "La fecha de inicio no es válida o está vacía");
            return;
        }
    
        if (!$this->fechaFin || !strtotime($this->fechaFin)) {
            $this->dispatch('mostrarToast', 'Continuar reporte', "La fecha de fin no es válida o está vacía");
            return;
        }
    
        $inicio = Carbon::parse($this->fechaInicio);
        $fin = Carbon::parse($this->fechaFin);
    
        if ($fin->greaterThan(Carbon::now())) {
            $this->dispatch('mostrarToast', 'Continuar reporte', "La fecha de fin debe ser posterior a la fecha de hoy");
            return;
        }

        if ($inicio->gt($fin)) {
            $this->dispatch('mostrarToast', 'Continuar reporte', "La fecha de fin debe ser posterior a la fecha de inicio");
            return;
        }
    
        if ($inicio->diffInDays($fin) > 15) {
            $this->dispatch('mostrarToast', 'Continuar reporte', "No puede haber más de 15 días entre fechas");
            return;
        }

        if($this->tipoReporte !="1" & $this->tipoReporte !="2"){
            $this->dispatch('mostrarToast', 'Continuar reporte', "Aún no ha seleccionado el tipo de reporte o no es una opción válida");
            return;
        }

        //Ahora, si son todos, continuo, sino, digo que adicione los estudios
        if($this->tipoReporte =="1"){
            //Cargo todos los estudios a los que seleccioné
            $this->estudiosSeleccionados=$this->informacion;

            //Indico que inicie el reporte
            $this->generarReporte();
        }else if($this->tipoReporte =="2"){
            if(empty($this->indexSeleccionados)){
                $this->dispatch('mostrarToast', 'Continuar reporte', "Aún no ha seleccionado estudios para generar el reporte");
                return;
            }

            //Ahora cargo todo entonces
            $this->estudiosSeleccionados=[];
            foreach($this->indexSeleccionados as $indices){
                $this->estudiosSeleccionados[]=$this->informacion[$indices];
            }

            //Indico que inicie el reporte
            $this->generarReporte();
        }
    }

    public function generarReporte(){
        //Indico que lo esoy ejecuttando
        $this->ejecutandoReporte=True;
        $this->reporteListo=False;

        //Cargo las fechas
        $fechaInicioFormateada = Carbon::parse($this->fechaInicio)
        ->setTime(0, 0)
        ->format('Y-m-d H:i');

        $fechaFinFormateada = Carbon::parse($this->fechaFin)
        ->addDay()         // suma un día
        ->setTime(0, 0)    // asegura que la hora sea 00:00
        ->format('Y-m-d H:i');


        //Mando la orden para que se corra el job
        ProcesarConsultaReportes::dispatch(Auth::user()->id,$this->estudiosSeleccionados,$fechaInicioFormateada,$fechaFinFormateada);

        registrarLog("Producción","Reportes","Generar reportes","Ha generado reporte ente las fechas ".$fechaInicioFormateada." y ".$fechaFinFormateada." para #".count($this->estudiosSeleccionados)." estudios",true);


    }

    public function progressDone(){
        //Si estaba ejecutando el reporte
        if($this->ejecutandoReporte){
            $this->ejecutandoReporte=False;
            $this->reporteListo=True;

            $this->dispatch('mostrarToast', 'Generar reporte', "Reporte terminado");

            $this->resultado= Cache::get("reportResult_" . Auth::user()->id);
            Cache::forget("reportResult_" . Auth::user()->id);

            //Reorganizo simulador
            foreach($this->resultado as $key=>$elemento){
                if (isset($elemento["ResultsReport"]['Paginas']['SIMULADOR'])) {
                    $valor = $elemento["ResultsReport"]['Paginas']['SIMULADOR'];
                    unset($this->resultado[$key]["ResultsReport"]['Paginas']['SIMULADOR']);     // Eliminar
                    $this->resultado[$key]["ResultsReport"]['Paginas']['SIMULADOR'] = $valor;   // Reinsertar al final
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.estudios.reporte');
    }
}
