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
}
