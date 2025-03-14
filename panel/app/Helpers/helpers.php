<?php

use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request; // Importar Request

if (!function_exists('logAccion')) {
    function registrarLog($menu,$section, $action, $details,$result=true)
    {
        //Analizo si estoy logueado
        if(Auth::check()){

            //Creo el log
            $log = new Log();
            $log->menu = $menu;
            $log->section = $section;
            $log->action = $action;
            $log->details = $details;
            $log->author=Auth::user()->id;
            $log->ip_address=Request::ip();
            $log->result=$result;

            $log->save();
        }
    }
}
