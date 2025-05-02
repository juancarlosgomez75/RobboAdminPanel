<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PanelController extends Controller
{
    public function profile_view(){
        return view("perfiles.perfil");
    }

    public function index(){
        return view("admin.index");
    }

    public function consulta(){
        $data_send=[
            'Branch' => 'Server',
            'Service' => 'PlatformUser',
            'Action' => 'StudyReport',
            'Data' => [
                "UserId" => "1",
                "ModelData"=>[
                    "InitialDate"=>"2025-04-15 00:00",
                    "FinalDate"=>"2025-05-01 00:00"
                ],
                "UserData"=>[
                    "Id"=>52
                ]
            ]
        ];
        //Obtengo la informacion que requiero
        $data=sendBack($data_send);

        return json_encode($data);
    }
}
