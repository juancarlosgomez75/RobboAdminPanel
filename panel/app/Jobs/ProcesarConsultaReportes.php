<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

use function PHPUnit\Framework\isFalse;

class ProcesarConsultaReportes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    //Variables que usaré
    public $studies;
    public $fechaInicio;
    public $fechaFin;
    public $userId;
    public $API_PROD=False;

    public $rentasCompartidas=[];
    public $montos=[];

    public $tipo;
    public function __construct($userId,$studies,$fechaInicio,$fechaFin,$API_URL,$rentas,$montos)
    {
        $this->userId = $userId;
        $this->studies=$studies;
        $this->fechaInicio=$fechaInicio;
        $this->fechaFin=$fechaFin;
        $this->rentasCompartidas=$rentas;
        $this->montos=$montos;

        if($API_URL=="production"){
            $this->API_PROD=True;
        }


        Cache::forget("reportProgress_".$userId);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // for ($i = 0; $i <= 10; $i++) {
        //     // Simula trabajo de 1 segundo
        //     sleep(1);

        //     // Actualiza progreso en %
        //     $progreso = $i * 10;
        //     Cache::put("reportProgress_".$this->userId, $progreso, 600);
        // }

        $total = count($this->studies);

        foreach($this->studies as $index =>$study){

            $data_send=[
                'Branch' => 'Server',
                'Service' => 'PlatformUser',
                'Action' => 'StudyReport',
                'Data' => [
                    "UserId" => "1",
                    "ModelData"=>[
                        "InitialDate"=>$this->fechaInicio,
                        "FinalDate"=>$this->fechaFin
                    ],
                    "UserData"=>[
                        "Id"=>$study["Id"]
                    ]
                ]
            ];
            //Obtengo la informacion que requiero
            $data=sendBack($data_send,$this->API_PROD);

            if(isset($data["Status"])){
                if($data["Status"]??False){
                    if($data["Data"]["DetailedReport"]?? False){
                        //Cargo la info cruda
                        $raw=$data["Data"]["DetailedReport"];
                        $resultado_estudio=[];

                        if(!empty($raw)){
                            //Defino lo que almacena
                            $resultado_estudio=[
                                "Maquinas"=>[],
                                "Acciones"=>[],
                                "Paginas"=>[],
                                "Modelos"=>[],
                                "Tokens"=>0
                            ];

                        }
                        //Añado el detailed
                        $this->studies[$index]["DetailedReport"]=$raw;
                        foreach($raw as $log){

                            $rentaCompartida = false;

                            // //Analizo si es renta fija o no
                            foreach ($this->rentasCompartidas as $renta) {
                                if (strpos(strtolower($log["DataStudy"]["StudyName"]), strtolower($renta)) !== false) {
                                    $rentaCompartida = true;
                                    break;
                                }
                            }
                            if(($rentaCompartida && $log["Tokens"]>=5) || !$rentaCompartida){

                                //Salto los test
                                if (strpos($log["ModelData"]["ModelUserName"], "test") !== false &&
                                    strpos($log["ModelData"]["ModelUserName"], "labtest") === false) {
                                    continue;
                                }

                                //Salto sucker
                                if ($log["ModelData"]["ModelUserName"]=="sucker_drool"){
                                    continue;
                                }

                                $maquina=$log["Machine"]["FirmwareID"];
                                //Analizo si ya tengo registrada la maquina o no
                                if(!array_key_exists($maquina, $resultado_estudio["Maquinas"])){
                                    $resultado_estudio["Maquinas"][$maquina]=[
                                        "Acciones"=>[],
                                        "Modelos"=>[],
                                        "Tokens"=>0
                                    ];
                                }

                                $modelo=$log["ModelData"]["ModelUserName"];
                                //Analizo si ya tengo registrado al modelo o no
                                if(!array_key_exists($modelo, $resultado_estudio["Modelos"])){
                                    $resultado_estudio["Modelos"][$modelo]=[
                                        "Acciones"=>[],
                                        "Paginas"=>[],
                                        "Tokens"=>0
                                    ];
                                }

                                if(!array_key_exists($modelo, $resultado_estudio["Maquinas"][$maquina]["Modelos"])){
                                    $resultado_estudio["Maquinas"][$maquina]["Modelos"][$modelo]=[
                                        "Tokens"=>0
                                    ];
                                }

                                //Analizo las acciiones generales, si no existte la creo, y si no le sumo
                                $accion=$log["Action"];
                                if(!array_key_exists($accion, $resultado_estudio["Acciones"])){
                                    $resultado_estudio["Acciones"][$accion]=[
                                        "Cantidad"=>0,
                                        "Tiempo"=>0
                                    ];
                                }
                                if(!array_key_exists($accion, $resultado_estudio["Modelos"][$modelo]["Acciones"])){
                                    $resultado_estudio["Modelos"][$modelo]["Acciones"][$accion]=[
                                        "Cantidad"=>0,
                                        "Tiempo"=>0
                                    ];
                                }
                                if(!array_key_exists($accion, $resultado_estudio["Maquinas"][$maquina]["Acciones"])){
                                    $resultado_estudio["Maquinas"][$maquina]["Acciones"][$accion]=[
                                        "Cantidad"=>0,
                                        "Tiempo"=>0
                                    ];
                                }

                                //Ahora aumento en acciones
                                $resultado_estudio["Acciones"][$accion]["Cantidad"]+=1;
                                $resultado_estudio["Acciones"][$accion]["Tiempo"]+=$log["ContratedValue"];

                                $resultado_estudio["Modelos"][$modelo]["Acciones"][$accion]["Cantidad"]+=1;
                                $resultado_estudio["Modelos"][$modelo]["Acciones"][$accion]["Tiempo"]+=$log["ContratedValue"];

                                $resultado_estudio["Maquinas"][$maquina]["Acciones"][$accion]["Cantidad"]+=1;
                                $resultado_estudio["Maquinas"][$maquina]["Acciones"][$accion]["Tiempo"]+=$log["ContratedValue"];

                                //Ahora a las páginas, inicio preguntando si es simulador o cual
                                $pagina = empty($log["Page"]) ? "SIMULADOR" : $log["Page"];
                                if(!array_key_exists($pagina , $resultado_estudio["Paginas"])){
                                    $resultado_estudio["Paginas"][$pagina]=[
                                        "Tokens"=>0,
                                        "Tipers"=>[]
                                    ];
                                }
                                if(!array_key_exists($pagina , $resultado_estudio["Modelos"][$modelo]["Paginas"])){
                                    $resultado_estudio["Modelos"][$modelo]["Paginas"][$pagina]=[
                                        "Tokens"=>0,
                                        "Tipers"=>[]
                                    ];
                                }

                                //Ahora registro los tokens al total
                                $resultado_estudio["Tokens"]+=$log["Tokens"];
                                $resultado_estudio["Modelos"][$modelo]["Tokens"]+=$log["Tokens"];

                                $resultado_estudio["Maquinas"][$maquina]["Tokens"]+=$log["Tokens"];
                                $resultado_estudio["Maquinas"][$maquina]["Modelos"][$modelo]["Tokens"]+=$log["Tokens"];

                                //Ahora por pagina
                                $resultado_estudio["Paginas"][$pagina]["Tokens"]+=$log["Tokens"];
                                $resultado_estudio["Modelos"][$modelo]["Paginas"][$pagina]["Tokens"]+=$log["Tokens"];


                                //Ahora los tipers
                                $tiper = $log["Typer"];
                                if(!array_key_exists($tiper , $resultado_estudio["Paginas"][$pagina]["Tipers"])){
                                    $resultado_estudio["Paginas"][$pagina]["Tipers"][$tiper]=[
                                        "Tokens"=>0
                                    ];
                                }
                                if(!array_key_exists($tiper , $resultado_estudio["Modelos"][$modelo]["Paginas"][$pagina]["Tipers"])){
                                    $resultado_estudio["Modelos"][$modelo]["Paginas"][$pagina]["Tipers"][$tiper]=[
                                        "Tokens"=>0
                                    ];
                                }

                                //Sumo
                                $resultado_estudio["Paginas"][$pagina]["Tipers"][$tiper]["Tokens"]+=$log["Tokens"];
                                $resultado_estudio["Modelos"][$modelo]["Paginas"][$pagina]["Tipers"][$tiper]["Tokens"]+=$log["Tokens"];

                            }

                        }

                        //Ahora modifico
                        $this->studies[$index]["ResultsReport"]=$resultado_estudio;

                        //Ahora analizo si es renta compartida o no
                        if($rentaCompartida){
                            $this->studies[$index]["Renta"]="Compartida";
                            $this->studies[$index]["Montos"]=$this->montos;
                                        //Genero los cobros totales
                            $this->studies[$index]["CobrosTotales"]=[
                                "MOV"=>number_format(($this->studies[$index]["ResultsReport"]["Acciones"]["MOV"]["Tiempo"] ?? 0)*$this->montos["MOV"] / 60, 2),
                                "CONTROL"=>number_format(($this->studies[$index]["ResultsReport"]["Acciones"]["CONTROL"]["Tiempo"] ?? 0)*$this->montos["CONTROL"] / 60, 2),
                                "CUM"=>number_format(($this->studies[$index]["ResultsReport"]["Acciones"]["CUM"]["Cantidad"] ?? 0)*$this->montos["CUM"], 2),
                                "SCUM"=>number_format(($this->studies[$index]["ResultsReport"]["Acciones"]["SCUM"]["Cantidad"] ?? 0)*$this->montos["SCUM"], 2),
                                "XCUM"=>number_format(($this->studies[$index]["ResultsReport"]["Acciones"]["XCUM"]["Cantidad"] ?? 0)*$this->montos["XCUM"], 2),
                            ];
                            $this->studies[$index]["CobrosTotales"]["Total"]=array_sum($this->studies[$index]["CobrosTotales"]);

                            //Genero los cobros por modelos
                            $this->studies[$index]["CobrosModelos"]=[];
                            foreach($this->studies[$index]["ResultsReport"]["Modelos"] as $modelo=>$valores){
                                $this->studies[$index]["CobrosModelos"][$modelo]=[];
                                $this->studies[$index]["CobrosModelos"][$modelo]["MOV"]=number_format(($valores["Acciones"]["MOV"]["Tiempo"] ?? 0)*$this->montos["MOV"] / 60, 2);
                                $this->studies[$index]["CobrosModelos"][$modelo]["CONTROL"]=number_format(($valores["Acciones"]["CONTROL"]["Tiempo"] ?? 0)*$this->montos["CONTROL"] / 60, 2);
                                $this->studies[$index]["CobrosModelos"][$modelo]["CUM"]=number_format(($valores["Acciones"]["CUM"]["Cantidad"] ?? 0)*$this->montos["CUM"], 2);
                                $this->studies[$index]["CobrosModelos"][$modelo]["SCUM"]=number_format(($valores["Acciones"]["SCUM"]["Cantidad"] ?? 0)*$this->montos["SCUM"], 2);
                                $this->studies[$index]["CobrosModelos"][$modelo]["XCUM"]=number_format(($valores["Acciones"]["XCUM"]["Cantidad"] ?? 0)*$this->montos["XCUM"], 2);

                                $this->studies[$index]["CobrosModelos"][$modelo]["Total"]=array_sum($this->studies[$index]["CobrosModelos"][$modelo]);
                            }
                        }else{
                            $this->studies[$index]["Renta"]="Fija";
                        }
                    }

                }
            }
            // sleep(1);

            // Actualiza la barra de progreso (ej: en caché)
            Cache::put("reportProgress_".$this->userId, round((($index + 1) / $total) * 100), 600);

        }

        Cache::put("reportResult_".$this->userId,  $this->studies, 600);
        // Cache::forget("reportProgress_".$this->userId);

    }
}
