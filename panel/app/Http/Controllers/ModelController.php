<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ModelController extends Controller
{

    public function view(){







        return view("modelos.listado");
    }

    public function create($idestudio){

        $data_send=[
            'Branch' => 'Server',
            'Service' => 'PlatformUser',
            'Action' => 'StudyInfo',
            'Data' => ["UserId" => "1"],
            "DataStudy"=>["Id"=>$idestudio]
        ];
        $dataStudio=sendBack($data_send);

        if (isset($dataStudio['Status'])){
            //Analizo si el status es correcto
            if($dataStudio['Status']){
                return view("modelos.create",compact("idestudio"));
            }
        }

        return "Estudio no encontrado";


    }

    public function viewedit($idmodelo){

        // //Genero la petición de información del modelo para ver si existe
        // $response = Http::withHeaders([
        //     'Authorization' => 'AAAA'
        // ])->withOptions([
        //     'verify' => false // Desactiva la verificación SSL
        // ])->post(config('app.API_URL'), [
        //     'Branch' => 'Server',
        //     'Service' => 'PlatformUser',
        //     'Action' => 'ModelInfo',
        //     'Data' => [
        //         "UserId" => "1",
        //         "ModelData"=>[
        //             "ModelId"=>$idmodelo
        //         ]
        //     ]
        // ]);

        // $data = $response->json();

        $data_send=[
            'Branch' => 'Server',
            'Service' => 'PlatformUser',
            'Action' => 'ModelInfo',
            'Data' => [
                "UserId" => "1",
                "ModelData"=>[
                    "ModelId"=>$idmodelo
                ]
            ]
        ];
        $data=sendBack($data_send);

        $data_sendRangos=[
            'Branch' => 'Server',
            'Service' => 'SelfModels',
            'Action' => 'GetConfig',
            'Data' => [
                "UserId" => "1",
                "ModelData"=>[
                    "ModelId"=>$idmodelo
                ],
                "UserData"=>[
                    "Id"=>1
                ]
            ]
        ];
        $dataRangos=sendBack($data_sendRangos);

        //Analizo si es válido lo que necesito
        if (isset($data['Status'])) {
            //Analizo si el status es true
            if($data["Status"]){
                return view("modelos.viewedit",["ModelInformation"=>$data["Data"]["ModelData"],"ManagerInformation"=>$data["Data"]["UserData"],"StudyInformation"=>$data["DataStudy"],"RanksInformation"=>$dataRangos["Data"]["ModelData"]["RangeValues"]??[]]);
            }
            return "Error de status";

        }


        return "Error general";
    }
}
