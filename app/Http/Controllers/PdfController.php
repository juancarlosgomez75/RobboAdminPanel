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
