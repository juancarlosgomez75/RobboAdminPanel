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

    public function mount($information){
        $this->informacion=$information;

        usort($this->informacion, function ($a, $b) {
            return strcmp($a["StudyName"], $b["StudyName"]);
        });
    }

    public function generarReporte(){

        ProcesarConsultaReportes::dispatch(Auth::user()->id,$this->informacion);
        $this->dispatch('mostrarToast', 'Activar job', "Iniciado el job");


    }

    public function render()
    {
        return view('livewire.estudios.reporte');
    }
}
