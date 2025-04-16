<?php

use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request; // Importar Request;
use Illuminate\Support\Facades\Http;

if (!function_exists('registrarLog')) {
    function registrarLog($menu,$section, $action, $details,$result=true)
    {
        //Analizo si estoy logueado
        if(Auth::check()){

            //Creo el log
            $log = new Log();
            $log->menu = $menu;
            $log->section = $section;
            $log->action = $action;
            $log->enviroment=session('API_used',"development");
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

if (!function_exists('sendBack')) {
    function sendBack($data,$code="AAA")
    {

        if(session('API_used',"development")=="production"){
            $api = config('app.API_URL_PROD');
        }
        else{
            $api = config('app.API_URL_DEV');
        }

        $response = Http::withHeaders([
            'Authorization' => $code
        ])->withOptions([
            'verify' => false // Desactiva la verificación SSL
        ])->post($api, $data);

        // Obtener la respuesta como string comprimido
        $compressedResponse = $response->body(); 

        // Descomprimir la respuesta
        $decompressedResponse = decompressString($compressedResponse);

        // Convertir el JSON descomprimido a array
        $data  = json_decode($decompressedResponse, true);


        return $data;
    }
}
