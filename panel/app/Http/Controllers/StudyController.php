<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StudyController extends Controller
{
    public function index(){

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
        ])->post(config('app.API_URL'), [
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
        $responseStudio = Http::withHeaders([
            'Authorization' => 'AAAA'
        ])->withOptions([
            'verify' => false // Desactiva la verificación SSL
        ])->post(config('app.API_URL'), [
            'Branch' => 'Server',
            'Service' => 'PlatformUser',
            'Action' => 'StudyInfo',
            'Data' => ["UserId" => "1"],
            "DataStudy"=>["Id"=>$idestudio]
        ]);

        $dataStudio = $responseStudio->json();

        //Genero la petición para obtener la información general
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

        //Si se recibe la informacion
        if (isset($dataStudio['Status']) && isset($generalinformation["Status"])){
            //Analizo si el status es correcto
            if($dataStudio['Status'] && $generalinformation["Status"]){

                // Mapear ciudades con su país
                $cityMap = [];
                if (isset($generalinformation['CountryList'])) {
                    foreach ($generalinformation['CountryList'] as $country) {
                        foreach ($country['Cities'] as $city) {
                            $cityMap[] = ["Id"=>$city["Id"],"Name"=>$city['CityName'] . ', ' . $country['CountryName']];
                        }
                    }
                }


                return view("estudios.viewedit",["Information"=> $dataStudio["DataStudy"],"Managers"=> $dataStudio["ListUserData"],"Machines"=> $dataStudio["Data"]["Machines"],"Ciudades"=>$cityMap]);
            }
        }


        return "Error";
    }

    public function manager_viewedit($idmanager){

        return view("estudios.manager-viewedit");
    }

    public function manager_create($idestudio){
        //Genero la petición de buscar a los managers de ese estudio
        $responseStudio = Http::withHeaders([
            'Authorization' => 'AAAA'
        ])->withOptions([
            'verify' => false // Desactiva la verificación SSL
        ])->post(config('app.API_URL'), [
            'Branch' => 'Server',
            'Service' => 'PlatformUser',
            'Action' => 'StudyInfo',
            'Data' => ["UserId" => "1"],
            "DataStudy"=>["Id"=>$idestudio]
        ]);

        $dataStudio = $responseStudio->json();

        //Si se recibe la informacion
        if (isset($dataStudio['Status'])){
            //Analizo si el status es correcto
            if($dataStudio['Status']){

                return view("estudios.manager-create",["IdEstudio"=>$idestudio]);
            }
        }

        return "Error";
    }

}
