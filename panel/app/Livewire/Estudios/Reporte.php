<?php

namespace App\Livewire\Estudios;

use App\Jobs\ProcesarConsultaReportes;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Reporte extends Component
{
    public $informacion;

    public $estudioSeleccionado;
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
