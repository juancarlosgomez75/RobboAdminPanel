<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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

            return "Error de permisos";
        }

        return "Error";
    }
}
