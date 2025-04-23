<?php

namespace App\Livewire\Estudios;

use Livewire\Component;

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
        $data_send=[
            'Branch' => 'Server',
            'Service' => 'PlatformUser',
            'Action' => 'StudyReport',
            'Data' => [
                "UserId" => "1",
                "ModelData"=>[
                    "InitialDate"=>"2024-04-01 00:00",
                    "FinalDate"=>"2024-04-16 00:00"
                ],
                "UserData"=>[
                    "Id"=>$this->estudioSeleccionado
                ]
            ]
        ];
        // $informacion_logs=sendBack($data_send);
        $this->resultado=sendBackPython($data_send);

    }

    public function render()
    {
        return view('livewire.estudios.reporte');
    }
}
