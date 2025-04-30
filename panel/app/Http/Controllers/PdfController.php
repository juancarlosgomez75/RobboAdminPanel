<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Cache;

Carbon::setLocale('es');

class PdfController extends Controller
{

    public function generatePdf()
    {
        // Cargar una vista y pasarle datos
        $data = ['name' => 'Juan', 'age' => 25];
        
        $pdf = Pdf::loadView('pdf_template', $data);
        
        // Descargar el PDF generado
        // return $pdf->download('documento.pdf');
        return $pdf->stream('documento.pdf');
    }

    public function generateReport(Request $request)
    {
        // Accede a los datos enviados por POST
        $information = json_decode($request->input('data'),true);

        $resultado= Cache::get($information["Variable"],False);

        if($resultado==False){
            return "Error con la información";
        }

        $data=$resultado[$information["Id"]];

        $fechaActual = Carbon::now();

        $fechaInicioRaw = Carbon::parse($data["FechaInicio"]);
        $fechaInicio = $fechaInicioRaw->translatedFormat('F d');

        $fechaFinRaw = Carbon::parse($data["FechaFin"]);
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
        // ini_set('max_execution_time', 300); // 5 minutos
        // ini_set('memory_limit', '512M');
        // Genera el PDF con los datos
        $pdfg = Pdf::loadView('report_template', compact('data','fechaActual','fechaInicio','fechaFin'));
    
        return $pdfg->stream('reporte.pdf'); // Muestra el PDF en el navegador
    }

    public function getReport()
    {
        // Cargar una vista y pasarle datos
        $data = ['name' => 'Juan', 'age' => 25];
        
        $pdf = Pdf::loadView('pdf_template', $data);
        
        // Descargar el PDF generado
        // return $pdf->download('documento.pdf');
        return view('viewcorreo');
    }


}
