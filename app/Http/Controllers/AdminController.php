<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function accounts(){
        //Consulto las cuentas
        return view("admin.accounts");
    }

    public function account($idcuenta){

        //Busco el usuario
        $usuario=User::find($idcuenta);

        //Si encuentra
        if($usuario){
            //Analizo si el rango coincide o si es mi usuario
            if($usuario->rank<Auth::user()->rank && Auth::user()->id!=$usuario->id){
                return view("admin.account",compact("usuario"));
            }

            return abort(403, 'No tienes permisos para acceder a esta página.');
        }

        return abort(404, 'No se ha encontrado este recurso');
    }

    public function logs(){
        return view("admin.logs");
    }

    public function log($logid){
        $log=Log::find($logid);

        if($log){
            return view("admin.log",compact("log"));
        }else{
            return redirect(route('admin.logs'));
        }
        
    }
}
