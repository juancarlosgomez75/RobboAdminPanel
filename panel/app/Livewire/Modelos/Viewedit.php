<?php

namespace App\Livewire\Modelos;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class Viewedit extends Component
{
    //Variables que se cargan
    public $ModelInformation;
    public $ManagerInformation;
    public $StudyInformation;
    public $editing=true;
    public $alerta=false;

    public $alerta_sucess="";
    public $alerta_error="";
    public $alerta_warning="";

    //Inicializo la información de la modelo
    public $drivername;
    public $usecustomname=0;
    public $customname="";
    public $estudioactual=0;
    public $manageractual=0;
    public $active=false;

    //Genero la variable de lista de managers y estudios
    public $listadoestudios;
    public $listadomanagers;

    public function obtenerEstudios(){

        //Genero la petición para obtener la información
        $information = Http::withHeaders([
            'Authorization' => 'AAAA'
        ])->withOptions([
            'verify' => false // Desactiva la verificación SSL
        ])->post(config('app.API_URL'), [
            'Branch' => 'Server',
            'Service' => "GeneralParams",
            'Action' => "GeneralParams",
            'Data' => ["UserId" => "1"]
        ]);

        $generalinformation=$information->json();

        //Genero la petición de informacion
        $response = Http::withHeaders([
            'Authorization' => 'AAAA'
        ])->withOptions([
            'verify' => false // Desactiva la verificación SSL
        ])->post(config('app.API_URL'), [
            'Branch' => 'Server',
            'Service' => 'PlatformUser',
            'Action' => 'StudyList',
            'Data' => ["UserId" => "1"]
        ]);

        $data = $response->json();

        //Analizo si todo okay
        if(isset($data['Status']) && isset($generalinformation['Status'])){
            //Analizo si el status es okay
            if($data["Status"] && $generalinformation["Status"]){
                if(isset($data["ListStudyData"])){

                    // Mapear ciudades con su país
                    $cityMap = [];
                    if (isset($generalinformation['CountryList'])) {
                        foreach ($generalinformation['CountryList'] as $country) {
                            foreach ($country['Cities'] as $city) {
                                $cityMap[$city['Id']] = $city['CityName'] . ',' . $country['CountryName'];
                            }
                        }
                    }

                    // Agregar el campo City en ListStudyData
                    if (isset($data['ListStudyData'])) {
                        foreach ($data['ListStudyData'] as &$study) {
                            $study['FullName'] = $study['StudyName'].' ('.($cityMap[$study['CityId']] ?? 'Desconocido').')';
                        }
                    }


                    //Significa que está bien, entonces proceso la lista
                    $this->listadoestudios=$data["ListStudyData"];

                    usort($this->listadoestudios, function ($a, $b) {
                        return strcmp($a["FullName"], $b["FullName"]);
                    });

                    return true;
                }
            }
        }

        $this->alerta=true;
        $this->alerta_error="Error obteniendo los estudios";
        return false;
    }

    public function obtenerManagers($idestudio){
        //Genero la petición de informacion
        $response = Http::withHeaders([
            'Authorization' => 'AAAA'
        ])->withOptions([
            'verify' => false // Desactiva la verificación SSL
        ])->post(config('app.API_URL'), [
            'Branch' => 'Server',
            'Service' => 'PlatformUser',
            'Action' => 'StudyInfo',
            'Data' => ["UserId" => "1"],
            "DataStudy"=> ["Id"=> $idestudio]
        ]);

        $data = $response->json();

        //Analizo si todo okay
        if(isset($data["Status"])){
            //Analizo si el status es okay
            if($data["Status"]){

                if(isset($data["ListUserData"])){
                    //Significa que está bien, entonces proceso la lista
                    $this->listadomanagers=$data["ListUserData"];

                    usort($this->listadomanagers, function ($a, $b) {
                        return strcmp($a["Name"], $b["Name"]);
                    });

                    return true;
                }
            }
        }

        $this->alerta=true;
        $this->alerta_error="Error obteniendo los managers";
        return false;
    }

    public function updatedestudioactual($valor)
    {
        $this->listadomanagers=[];
        $this->manageractual=0;
        $this->obtenerManagers($this->estudioactual);
    }

    public function mount($ModelInformation,$ManagerInformation,$StudyInformation){
        //Cargo la información principal
        $this->ModelInformation = $ModelInformation;
        $this->ManagerInformation = $ManagerInformation;
        $this->StudyInformation = $StudyInformation;

        //Cargo la información de la modelo
        $this->drivername = $ModelInformation["ModelUserName"];

        $this->usecustomname = ($ModelInformation["ModelPersonalCm"] ?? false) ? 1 : 0;
        $this->customname = $ModelInformation["ModelPersonalCmName"]??"";
        $this->active= $ModelInformation["ModelActive"];
        $this->estudioactual = $StudyInformation["Id"];
        $this->manageractual = $ManagerInformation["Id"];

        //Trato de obtener los estudios
        if($this->obtenerEstudios()){
            //Trato de obtener los managers
            if($this->obtenerManagers($this->estudioactual)){

            }
        }

    }
    public function render()
    {
        return view('livewire.modelos.viewedit');
    }
}
