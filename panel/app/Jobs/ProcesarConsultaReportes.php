<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ProcesarConsultaReportes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    //Variables que usaré
    public $studies;
    public $tipo;
    public function __construct($studies,$tipo)
    {
        $this->studies=$studies;
        $this->tipo=$tipo;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //
    }
}
