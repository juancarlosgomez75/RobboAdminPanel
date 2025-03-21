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

if (!function_exists('compressString')) {
    function compressString(string $string): string
    {
        $compressed = gzencode($string);
        return $compressed !== false ? base64_encode($compressed) : '';
    }
}

if (!function_exists('decompressString')) {
    function decompressString(string $encoded): string
    {
        $decoded = base64_decode($encoded, true);
        if ($decoded === false) {
            return '';
        }

        $decompressed = gzdecode($decoded);
        return $decompressed !== false ? $decompressed : '';
    }
}
