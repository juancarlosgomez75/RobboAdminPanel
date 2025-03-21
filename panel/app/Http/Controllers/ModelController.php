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
    
    public function create(){
        
        return view("modelos.create");
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

        //Analizo si es válido lo que necesito
        if (isset($data['Status'])) {
            //Analizo si el status es true
            if($data["Status"]){
                return view("modelos.viewedit",["ModelInformation"=>$data["Data"]["ModelData"],"ManagerInformation"=>$data["Data"]["UserData"],"StudyInformation"=>$data["DataStudy"]]);
            }
            return "Error de status";
            
        }
        
        
        return "Error general";
    }
}
