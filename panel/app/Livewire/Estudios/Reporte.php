<?php

namespace App\Livewire\Estudios;

use App\Jobs\ProcesarConsultaReportes;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class Reporte extends Component
{
    public $informacion;

    public $tipoReporte="0";
    public $resultadoObtenido=false;
    public $resultado;

    //Variables del registro
    public $fechaInicio;
    public $fechaFin;

    public $ejecutandoReporte=false;
    public $reporteListo=false;

    protected $listeners = ['progressDone'];

    public function mount($information){
        $this->informacion=$information;

        usort($this->informacion, function ($a, $b) {
            return strcmp($a["StudyName"], $b["StudyName"]);
        });
    }

    public function continuarReporte(){
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
    
        if ($inicio->gt($fin)) {
            $this->dispatch('mostrarToast', 'Continuar reporte', "La fecha de fin debe ser pstterior a la fecha de inicio");
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
    }

    public function generarReporte(){
        //Indico que lo esoy ejecuttando
        $this->ejecutandoReporte=True;
        $this->reporteListo=False;

        //Mando la orden para que se corra el job
        ProcesarConsultaReportes::dispatch(Auth::user()->id,$this->informacion);


    }

    public function progressDone(){
        //Si estaba ejecutando el reporte
        if($this->ejecutandoReporte){
            $this->ejecutandoReporte=False;
            $this->reporteListo=True;

            $this->dispatch('mostrarToast', 'Generar reporte', "Reporte terminado");
        }
    }

    public function render()
    {
        return view('livewire.estudios.reporte');
    }
}
