<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StudyController extends Controller
{
    public function index(){

        // //Genero la petición para obtener la información
        // $information = Http::withHeaders([
        //     'Authorization' => 'AAAA'
        // ])->withOptions([
        //     'verify' => false // Desactiva la verificación SSL
        // ])->post(config('app.API_URL'), [
        //     'Branch' => 'Server',
        //     'Service' => "GeneralParams",
        //     'Action' => "GeneralParams",
        //     'Data' => ["UserId" => "1"]
        // ]);

        // $generalinformation=$information->json();

        $data_send=[
            'Branch' => 'Server',
            'Service' => "GeneralParams",
            'Action' => "GeneralParams",
            'Data' => ["UserId" => "1"]
        ];
        $generalinformation=sendBack($data_send);
        
        // //Genero la petición de informacion
        // $response = Http::withHeaders([
        //     'Authorization' => 'AAAA'
        // ])->withOptions([
        //     'verify' => false // Desactiva la verificación SSL
        // ])->post(config('app.API_URL'), [
        //     'Branch' => 'Server',
        //     'Service' => 'PlatformUser',
        //     'Action' => 'StudyList',
        //     'Data' => ["UserId" => "1"]
        // ]);

        // $data = $response->json();

        $data_send=[
            'Branch' => 'Server',
            'Service' => 'PlatformUser',
            'Action' => 'StudyList',
            'Data' => ["UserId" => "1"]
        ];
        $data=sendBack($data_send);
        

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

        // //Genero la petición para obtener la información
        // $information = Http::withHeaders([
        //     'Authorization' => 'AAAA'
        // ])->withOptions([
        //     'verify' => false // Desactiva la verificación SSL
        // ])->post(config('app.API_URL'), [
        //     'Branch' => 'Server',
        //     'Service' => "GeneralParams",
        //     'Action' => "GeneralParams",
        //     'Data' => ["UserId" => "1"]
        // ]);

        // $generalinformation=$information->json();

        $data_send=[
                'Branch' => 'Server',
                'Service' => "GeneralParams",
                'Action' => "GeneralParams",
                'Data' => ["UserId" => "1"]
            ];
        $generalinformation=sendBack($data_send);

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

                usort($cityMap, function ($a, $b) {
                    return strcmp($a["Name"], $b["Name"]);
                });

                return view("estudios.create",["Ciudades"=>$cityMap]);
            }
            
        }
        
        
        return "Error";

        
        
    }

    public function viewedit($idestudio){

        // //Genero la petición de buscar a los managers de ese estudio
        // $responseStudio = Http::withHeaders([
        //     'Authorization' => 'AAAA'
        // ])->withOptions([
        //     'verify' => false // Desactiva la verificación SSL
        // ])->post(config('app.API_URL'), [
        //     'Branch' => 'Server',
        //     'Service' => 'PlatformUser',
        //     'Action' => 'StudyInfo',
        //     'Data' => ["UserId" => "1"],
        //     "DataStudy"=>["Id"=>$idestudio]
        // ]);

        // $dataStudio = $responseStudio->json();
        $data_send=[
            'Branch' => 'Server',
            'Service' => 'PlatformUser',
            'Action' => 'StudyInfo',
            'Data' => ["UserId" => "1"],
            "DataStudy"=>["Id"=>$idestudio]
        ];
        $dataStudio=sendBack($data_send);

        // //Genero la petición para obtener la información general
        // $information = Http::withHeaders([
        //     'Authorization' => 'AAAA'
        // ])->withOptions([
        //     'verify' => false // Desactiva la verificación SSL
        // ])->post(config('app.API_URL'), [
        //     'Branch' => 'Server',
        //     'Service' => "GeneralParams",
        //     'Action' => "GeneralParams",
        //     'Data' => ["UserId" => "1"]
        // ]);

        // $generalinformation=$information->json();

        $data_send=[
            'Branch' => 'Server',
            'Service' => "GeneralParams",
            'Action' => "GeneralParams",
            'Data' => ["UserId" => "1"]
        ];
        $generalinformation=sendBack($data_send);

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
        //Genero la petición de buscar a los managers de ese estudio
        // $response= Http::withHeaders([
        //     'Authorization' => 'AAAA'
        // ])->withOptions([
        //     'verify' => false // Desactiva la verificación SSL
        // ])->post(config('app.API_URL'), [
        //     'Branch' => 'Server',
        //     'Service' => 'PlatformUser',
        //     'Action' => 'ViewUser',
        //     'Data' => [
        //         "UserId" => "1",
        //         "UserData"=>[
        //             "Id"=>$idmanager
        //         ]
        //     ]
        // ]);

        // $dataManager = $response->json();

        $data_send=[
            'Branch' => 'Server',
            'Service' => 'PlatformUser',
            'Action' => 'ViewUser',
            'Data' => [
                "UserId" => "1",
                "UserData"=>[
                    "Id"=>$idmanager
                ]
            ]
        ];
        $dataManager=sendBack($data_send);

        //Si se recibe la informacion
        if (isset($dataManager['Status'])){
            //Analizo si el status es correcto
            if($dataManager['Status']){
                return view("estudios.manager-viewedit",["Information"=>$dataManager['Data']["UserData"],"Models"=>$dataManager['ListModelData'],"Study"=>$dataManager['DataStudy']]);
            }else{
                return "Error: ".$dataManager["Error"];
            }
        }

        return "Error";
    }

    public function manager_create($idestudio){
        // //Genero la petición de buscar a los managers de ese estudio
        // $responseStudio = Http::withHeaders([
        //     'Authorization' => 'AAAA'
        // ])->withOptions([
        //     'verify' => false // Desactiva la verificación SSL
        // ])->post(config('app.API_URL'), [
        //     'Branch' => 'Server',
        //     'Service' => 'PlatformUser',
        //     'Action' => 'StudyInfo',
        //     'Data' => ["UserId" => "1"],
        //     "DataStudy"=>["Id"=>$idestudio]
        // ]);

        // $dataStudio = $responseStudio->json();

        $data_send=[
            'Branch' => 'Server',
            'Service' => 'PlatformUser',
            'Action' => 'StudyInfo',
            'Data' => ["UserId" => "1"],
            "DataStudy"=>["Id"=>$idestudio]
        ];
        $dataStudio=sendBack($data_send);

        //Si se recibe la informacion
        if (isset($dataStudio['Status'])){
            //Analizo si el status es correcto
            if($dataStudio['Status']){

                return view("estudios.manager-create",["IdEstudio"=>$idestudio]);
            }else{
                return "Error: ".$dataStudio["Error"];
            }
        }

        return "Error";
    }

    public function report(){

        // //Genero la petición para obtener la información
        // $information = Http::withHeaders([
        //     'Authorization' => 'AAAA'
        // ])->withOptions([
        //     'verify' => false // Desactiva la verificación SSL
        // ])->post(config('app.API_URL'), [
        //     'Branch' => 'Server',
        //     'Service' => "GeneralParams",
        //     'Action' => "GeneralParams",
        //     'Data' => ["UserId" => "1"]
        // ]);

        // $generalinformation=$information->json();

        $data_send=[
            'Branch' => 'Server',
            'Service' => "GeneralParams",
            'Action' => "GeneralParams",
            'Data' => ["UserId" => "1"]
        ];
        $generalinformation=sendBack($data_send);
        
        // //Genero la petición de informacion
        // $response = Http::withHeaders([
        //     'Authorization' => 'AAAA'
        // ])->withOptions([
        //     'verify' => false // Desactiva la verificación SSL
        // ])->post(config('app.API_URL'), [
        //     'Branch' => 'Server',
        //     'Service' => 'PlatformUser',
        //     'Action' => 'StudyList',
        //     'Data' => ["UserId" => "1"]
        // ]);

        // $data = $response->json();

        $data_send=[
            'Branch' => 'Server',
            'Service' => 'PlatformUser',
            'Action' => 'StudyList',
            'Data' => ["UserId" => "1"]
        ];
        $data=sendBack($data_send);
        

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
                return view("estudios.reporte",["information"=>$data["ListStudyData"]]);
            }
            return "Error de status";
            
        }
        
        
        return "Error general";
    }

}

