<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use App\Mail\EnviarReporte;

class ProcesarEnvioReportes implements ShouldQueue
{
    use Queueable;

    //Variables que usaré
    public $studies;
    public $userId;

    public function __construct($userId,$studies)
    {
        $this->userId = $userId;
        $this->studies=$studies;

        Cache::forget("reportSendProgress_".$userId);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $total = count($this->studies);
        foreach($this->studies as $index =>$datos){

            //Genero el reporte PDF
            $pdfResponse = generateReportPDF($datos);

            //Genero la informacion
            $infoReply=array_diff_key($datos, array_flip(['DetailedReport', 'ResultsReport']));

            //Envio el correo
            // $sendto=$datos["Email"];
            $sendto="daenloye@gmail.com";

            Mail::to($sendto)->send(new EnviarReporte("Reporte por periodo", $pdfResponse,'administracion@coolsofttechnology.com',$infoReply ));

            // Actualiza la barra de progreso (ej: en caché)
            Cache::put("reportSendProgress_".$this->userId, round((($index + 1) / $total) * 100), 600);
        }
    }
}
