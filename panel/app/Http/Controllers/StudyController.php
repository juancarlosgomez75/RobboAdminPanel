<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StudyController extends Controller
{
    public $API="https://robbocock.online:8443/WSIntegration-1.0/resources/restapi/transaction";
    public function index(){

        //Genero la petición para obtener la información
        $information = Http::withHeaders([
            'Authorization' => 'AAAA'
        ])->withOptions([
            'verify' => false // Desactiva la verificación SSL
        ])->post($this->API, [
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
        ])->post($this->API, [
            'Branch' => 'Server',
            'Service' => 'PlatformUser',
            'Action' => 'StudyList',
            'Data' => ["UserId" => "1"]
        ]);

        $data = $response->json();

        //Analizo si es válido lo que necesito
        if (isset($data['Status']) && isset($generalinformation['Status'])) {
            //Analizo si el status es true
            if($data["Status"] && $generalinformation["Status"]){

                // Mapear ciudades con su país
                $cityMap = [];
                if (isset($generalinformation['CountryList'])) {
                    foreach ($generalinformation['CountryList'] as $country) {
                        foreach ($country['Cities'] as $city) {
                            $cityMap[$city['Id']] = $city['CityName'] . ', ' . $country['CountryName'];
                        }
                    }
                }

                // Agregar el campo City en ListStudyData
                if (isset($data['ListStudyData'])) {
                    foreach ($data['ListStudyData'] as &$study) {
                        $study['City'] = $cityMap[$study['CityId']] ?? 'Desconocido';
                    }
                }
                return view("estudios.index",["information"=>$data["ListStudyData"]]);
            }
            return "Error de status";
            
        }
        
        
        return "Error general";
    }

    public function create(){

        //Genero la petición para obtener la información
        $information = Http::withHeaders([
            'Authorization' => 'AAAA'
        ])->withOptions([
            'verify' => false // Desactiva la verificación SSL
        ])->post($this->API, [
            'Branch' => 'Server',
            'Service' => "GeneralParams",
            'Action' => "GeneralParams",
            'Data' => ["UserId" => "1"]
        ]);

        $generalinformation=$information->json();

        //Analizo si es válido lo que necesito
        if (isset($generalinformation['Status'])) {
            //Analizo si el status es true
            if($generalinformation["Status"]){



                // Mapear ciudades con su país
                $cityMap = [];
                if (isset($generalinformation['CountryList'])) {
                    foreach ($generalinformation['CountryList'] as $country) {
                        foreach ($country['Cities'] as $city) {
                            $cityMap[] = ["Id"=>$city["Id"],"Name"=>$city['CityName'] . ', ' . $country['CountryName']];
                        }
                    }
                }
                return view("estudios.create",["Ciudades"=>$cityMap]);
            }
            
        }
        
        
        return "Error";

        
        
    }

    public function viewedit($idestudio){

        //Genero la petición de buscar a los managers de ese estudio
        $responseManagers = Http::withHeaders([
            'Authorization' => 'AAAA'
        ])->withOptions([
            'verify' => false // Desactiva la verificación SSL
        ])->post($this->API, [
            'Branch' => 'Server',
            'Service' => 'PlatformUser',
            'Action' => 'UserList',
            'Data' => ["UserId" => "1"],
            "DataStudy"=>["Id"=>$idestudio]
        ]);

        $dataManagers = $responseManagers->json();

        //Genero la petición para obtener la información general
        $information = Http::withHeaders([
            'Authorization' => 'AAAA'
        ])->withOptions([
            'verify' => false // Desactiva la verificación SSL
        ])->post($this->API, [
            'Branch' => 'Server',
            'Service' => "GeneralParams",
            'Action' => "GeneralParams",
            'Data' => ["UserId" => "1"]
        ]);

        $generalinformation=$information->json();

        //Si se recibe la informacion
        if (isset($dataManagers['Status']) && isset($generalinformation["Status"])){
            //Analizo si el status es correcto
            if($dataManagers['Status'] && $generalinformation["Status"]){

                // Mapear ciudades con su país
                $cityMap = [];
                if (isset($generalinformation['CountryList'])) {
                    foreach ($generalinformation['CountryList'] as $country) {
                        foreach ($country['Cities'] as $city) {
                            $cityMap[] = ["Id"=>$city["Id"],"Name"=>$city['CityName'] . ', ' . $country['CountryName']];
                        }
                    }
                }


                return view("estudios.viewedit",["EstudioActual"=>$idestudio,"Managers"=> $dataManagers["ListUserData"],"Ciudades"=>$cityMap,"CiudadActual"=>"5"]);
            }
        }


        return "Error";
    }

    public function manager_viewedit($idmanager){

        return view("estudios.manager-viewedit");
    }

    public function manager_create($idestudio){

        return view("estudios.manager-create");
    }

}
