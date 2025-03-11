<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MachineController extends Controller
{
    public function index(){

        $maquinas=[
            ["Id"=>"102","Hardware"=>"200009","Tipo"=>"Cum","Estudio"=>"Casa"],
            ["Id"=>"103","Hardware"=>"200010","Tipo"=>"Fuck","Estudio"=>"Sabaneta"]
        ];
        return view("maquinas.index",["Maquinas"=>$maquinas]);

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
                        $study['FullName'] = $study['FullName'] = $study["StudyName"] . " (" . ($cityMap[$study['CityId']] ?? 'Desconocido') . ")";
                    }
                }

                usort($data["ListStudyData"], function ($a, $b) {
                    return strcmp($a["FullName"], $b["FullName"]);
                });

                return view("maquinas.create",["information"=>$data["ListStudyData"]]);
            }
            return "Error de status";
            
        }
        
        
        return "Error general";






    }
}
