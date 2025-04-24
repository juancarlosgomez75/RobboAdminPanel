<?php

use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request; // Importar Request;
use Illuminate\Support\Facades\Http;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

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
    function sendBack($data,$code="AAA",$produccionForced=False)
    {

        if(session('API_used',"development")=="production" || $produccionForced){
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

if (!function_exists('sendBackPython')) {
    function sendBackPython($data)
    {

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.config('app.API_PYTHON_REPORTES_KEY')
        ])->withOptions([
            'verify' => false // Desactiva la verificación SSL
        ])->post(config('app.API_PYTHON_REPORTES'), $data);

        // Convertir el JSON descomprimido a array
        $data  = json_decode($response->body(), true);


        return $data;
    }

}

if (!function_exists('generateReportPDF')) {
    function generateReportPDF($data)
    {
        $fechaActual = Carbon::now();

        $fechaInicioRaw = Carbon::parse($data["FechaInicio"]);
        $fechaInicio = $fechaInicioRaw->translatedFormat('F d');

        $fechaFinRaw = Carbon::parse($data["FechaFin"])->subDay();
        $fechaFin = $fechaFinRaw->translatedFormat('F d');

        //Analizo el tipo de renta es compartida, para generar entonces el reporte
        if($data["Renta"]=="Compartida"){

            //Genero los cobros totales
            $data["CobrosTotales"]=[
                "MOV"=>number_format(($data["ResultsReport"]["Acciones"]["MOV"]["Tiempo"] ?? 0)*$data["Montos"]["MOV"] / 60, 2),
                "CONTROL"=>number_format(($data["ResultsReport"]["Acciones"]["CONTROL"]["Tiempo"] ?? 0)*$data["Montos"]["CONTROL"] / 60, 2),
                "CUM"=>number_format(($data["ResultsReport"]["Acciones"]["CUM"]["Cantidad"] ?? 0)*$data["Montos"]["CUM"], 2),
                "SCUM"=>number_format(($data["ResultsReport"]["Acciones"]["SCUM"]["Cantidad"] ?? 0)*$data["Montos"]["SCUM"], 2),
                "XCUM"=>number_format(($data["ResultsReport"]["Acciones"]["XCUM"]["Cantidad"] ?? 0)*$data["Montos"]["XCUM"], 2),
            ];
            $data["CobrosTotales"]["Total"]=array_sum($data["CobrosTotales"]);

            //Genero los cobros por modelos
            $data["CobrosModelos"]=[];
            foreach($data["ResultsReport"]["Modelos"] as $modelo=>$valores){
                $data["CobrosModelos"][$modelo]=[];
                $data["CobrosModelos"][$modelo]["MOV"]=number_format(($valores["Acciones"]["MOV"]["Tiempo"] ?? 0)*$data["Montos"]["MOV"] / 60, 2);
                $data["CobrosModelos"][$modelo]["CONTROL"]=number_format(($valores["Acciones"]["CONTROL"]["Tiempo"] ?? 0)*$data["Montos"]["CONTROL"] / 60, 2);
                $data["CobrosModelos"][$modelo]["CUM"]=number_format(($valores["Acciones"]["CUM"]["Cantidad"] ?? 0)*$data["Montos"]["CUM"], 2);
                $data["CobrosModelos"][$modelo]["SCUM"]=number_format(($valores["Acciones"]["SCUM"]["Cantidad"] ?? 0)*$data["Montos"]["SCUM"], 2);
                $data["CobrosModelos"][$modelo]["XCUM"]=number_format(($valores["Acciones"]["XCUM"]["Cantidad"] ?? 0)*$data["Montos"]["XCUM"], 2);
                
                $data["CobrosModelos"][$modelo]["Total"]=array_sum($data["CobrosModelos"][$modelo]);
            }
        }
        // Genera el PDF con los datos
        $pdf = Pdf::loadView('report_template', compact('data','fechaActual','fechaInicio','fechaFin'));
    
        return $pdf->output();
    
    }
    
}
