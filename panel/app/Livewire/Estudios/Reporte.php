<?php

namespace App\Livewire\Estudios;

use App\Jobs\ProcesarConsultaReportes;
use App\Jobs\ProcesarEnvioReportes;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

use Illuminate\Support\Facades\Mail;
use App\Mail\EnviarReporte;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Http;

class Reporte extends Component
{
    public $informacion;

    public $tipoReporte="0";
    public $resultadoObtenido=false;
    public $resultado=[];

    //Variables del registro
    public $fechaInicio;
    public $fechaFin;
    public $fechaHoy;

    public $estudiosSeleccionados=[];
    public $indexSeleccionados=[];
    public $estudioActual="0";

    public $ejecutandoReporte=false;
    public $reporteListo=false;

    public $enviandoReportes=False;

    protected $listeners = ['progressDone','progressSendDone'];

    public $accionesInteres=["CUMTEST","MOVTEST","CONTROL","MOV","CUM","SCUM","XCUM"];

    public $rentasCompartidas=["Dreams","EAW","Colombia Cam","Sensattion","Vanessa","Labo"];

    public $tiemposConexion=[];

    public function mount($information){
        $this->informacion=$information;

        usort($this->informacion, function ($a, $b) {
            return strcmp($a["StudyName"], $b["StudyName"]);
        });

        $this->fechaHoy=Carbon::today()->toDateString();
    }

    public function adicionarEstudio(){
        //Analizo que no sea cero o que ya esté
        if($this->estudioActual<=0 | in_array($this->estudioActual-1,$this->indexSeleccionados) | $this->estudioActual>count($this->informacion)){
            $this->dispatch('mostrarToast', 'Continuar reporte', "El estudio no es válido, está vacío o ya fue añadido");
            return;
        }

        $this->indexSeleccionados[]=$this->estudioActual-1;
        $this->estudioActual="0";
    }

    public function quitarEstudio($index){
        // Eliminar el índice del array
        unset($this->indexSeleccionados[$index]);

        // Reindexar el array para reorganizar los índices
        $this->indexSeleccionados = array_values($this->indexSeleccionados);
    }

    public function completarReporte(){
        //Valido las fechas
        if (!$this->fechaInicio || !strtotime($this->fechaInicio)) {
            $this->dispatch('mostrarToast', 'Continuar reporte', "La fecha de inicio no es válida o está vacía");
            return;
        }
    
        if (!$this->fechaFin || !strtotime($this->fechaFin)) {
            $this->dispatch('mostrarToast', 'Continuar reporte', "La fecha de fin no es válida o está vacía");
            return;
        }
    
        $inicio = Carbon::parse($this->fechaInicio);
        $fin = Carbon::parse($this->fechaFin);
    
        if ($fin->greaterThan(Carbon::now())) {
            $this->dispatch('mostrarToast', 'Continuar reporte', "La fecha de fin debe ser posterior a la fecha de hoy");
            return;
        }

        if ($inicio->gt($fin)) {
            $this->dispatch('mostrarToast', 'Continuar reporte', "La fecha de fin debe ser posterior a la fecha de inicio");
            return;
        }
    
        if ($inicio->diffInDays($fin) > 32) {
            $this->dispatch('mostrarToast', 'Continuar reporte', "No puede haber más de 31 días entre fechas");
            return;
        }

        if($this->tipoReporte !="1" & $this->tipoReporte !="2"){
            $this->dispatch('mostrarToast', 'Continuar reporte', "Aún no ha seleccionado el tipo de reporte o no es una opción válida");
            return;
        }

        //Ahora, si son todos, continuo, sino, digo que adicione los estudios
        if($this->tipoReporte =="1"){
            //Cargo todos los estudios a los que seleccioné
            $this->estudiosSeleccionados=$this->informacion;

            //Indico que inicie el reporte
            $this->generarReporte();
        }else if($this->tipoReporte =="2"){
            if(empty($this->indexSeleccionados)){
                $this->dispatch('mostrarToast', 'Continuar reporte', "Aún no ha seleccionado estudios para generar el reporte");
                return;
            }

            //Ahora cargo todo entonces
            $this->estudiosSeleccionados=[];
            foreach($this->indexSeleccionados as $indices){
                $this->estudiosSeleccionados[]=$this->informacion[$indices];
            }

            //Indico que inicie el reporte
            $this->generarReporte();
        }
    }

    public function generarReporte(){
        //Indico que lo esoy ejecuttando
        $this->ejecutandoReporte=True;
        $this->reporteListo=False;

        //Cargo las fechas
        $fechaInicioFormateada = Carbon::parse($this->fechaInicio)
        ->format('Y-m-d');

        $fechaFinFormateada = Carbon::parse($this->fechaFin)
        ->format('Y-m-d');

        //Consulto el JSON de tiempos de conexión
        $data_send=[
            'Branch' => 'Server',
            'Service' => 'PlatformUser',
            'Action' => 'CxTimeReport',
            'Data' => [
                "UserId" => "1",
                "ModelData"=>[
                    "InitialDate"=>$this->fechaInicio,
                    "FinalDate"=>$this->fechaFin
                ],
            ]
        ];
        //Obtengo la informacion que requiero
        $this->tiemposConexion=[];

        $data=sendBack($data_send);
        if(isset($data["Status"])){
            if($data["Status"]??False){
                // // Crear el periodo de fechas
                // $periodo = CarbonPeriod::create($fechaInicioFormateada, $fechaFinFormateada);
                
                // // Generar el array de fechas
                // $fechas = [];
                // foreach ($periodo as $fecha) {
                //     $fechas[] = $fecha->format('Y-m-d');
                // }

                //Ahora recorro y formateo
                foreach($data["Data"]["CxTimeArchives"] as $index=>$archive){
                    if($archive["ArchiveExist"]){
                        //Decodifico
                        $decoded = base64_decode($archive["Archive"], true);
                        
                        //Si no logra decodificar que salte
                        if ($decoded === false) {
                            continue;
                        }

                        //Ahora almaceno
                        $this->tiemposConexion[$archive["ArchiveDate"]]=json_decode($decoded, true);

                    }
                }
            }
        }

        //Mando la orden para que se corra el job
        ProcesarConsultaReportes::dispatch(Auth::user()->id,$this->estudiosSeleccionados,$fechaInicioFormateada,$fechaFinFormateada,session('API_used',"development"),$this->rentasCompartidas,[
                            "MOV"=>0.59,
                            "CONTROL"=>0.59,
                            "CUM"=>1.39,
                            "SCUM"=>2.39,
                            "XCUM"=>0.69,
                        ]);

        registrarLog("Producción","Reportes","Generar reportes","Ha generado reporte ente las fechas ".$fechaInicioFormateada." y ".$fechaFinFormateada." para #".count($this->estudiosSeleccionados)." estudios",true);


    }

    public function enviarTodosReportes(){
        $enviar=[];
        //Analizo si los resultados son mayores

        foreach($this->resultado as $elemento){
            if(array_key_exists("ResultsReport", $elemento)){
                $enviar[]=$elemento;
            }
        }

        if(count($enviar)==0){
            $this->dispatch('mostrarToast', 'Enviar todos los reportes', "No hay reportes para enviar");
            return;
        }

        $this->enviandoReportes=True;

        ProcesarEnvioReportes::dispatch(Auth::user()->id,$enviar);

        //Cargo las fechas
        $fechaInicioFormateada = Carbon::parse($this->fechaInicio)
        ->setTime(0, 0)
        ->format('Y-m-d H:i');

        $fechaFinFormateada = Carbon::parse($this->fechaFin)
        ->addDay()         // suma un día
        ->setTime(0, 0)    // asegura que la hora sea 00:00
        ->format('Y-m-d H:i');

        registrarLog("Producción","Reportes","Enviar todos los reportes","Ha enviado los reportes ente las fechas ".$fechaInicioFormateada." y ".$fechaFinFormateada." para #".count($this->resultado)." estudios",true);
    }
    public function progressSendDone(){
        if($this->enviandoReportes){
            $this->enviandoReportes=False;
            Cache::forget("reportSendResult_" . Auth::user()->id);
            $this->dispatch('mostrarToast', 'Enviar todos los reportes', "Todos los reportes han sido enviados");
        }
    }

    public function progressDone(){
        //Si estaba ejecutando el reporte
        if($this->ejecutandoReporte){
            $this->ejecutandoReporte=False;
            $this->reporteListo=True;

            // $this->dispatch('mostrarToast', 'Generar reporte', "Reporte terminado");

            $this->resultado= Cache::get("reportResult_" . Auth::user()->id,False);
            // Cache::forget("reportResult_" . Auth::user()->id);

            //Reorganizo simulador y meto otras variables de inttterés
            if($this->resultado != False){
                foreach($this->resultado as $key=>$elemento){
                    if (isset($elemento["ResultsReport"]['Paginas']['SIMULADOR'])) {
                        $valor = $elemento["ResultsReport"]['Paginas']['SIMULADOR'];
                        unset($this->resultado[$key]["ResultsReport"]['Paginas']['SIMULADOR']);     // Eliminar
                        $this->resultado[$key]["ResultsReport"]['Paginas']['SIMULADOR'] = $valor;   // Reinsertar al final
                        $this->resultado[$key]["FechaInicio"]=$this->fechaInicio;
                        $this->resultado[$key]["FechaFin"]=$this->fechaFin;
                    }
    
                    //Ahora analizo el tipo de renta que es
                    $rentaCompartida = false;
    
                    foreach ($this->rentasCompartidas as $renta) {
                        if (strpos(strtolower($elemento["StudyName"]), strtolower($renta)) !== false) {
                            $rentaCompartida = true;
                            break;
                        }
                    }
    
                    $this->resultado[$key]["CustomMail"]="";

                    // //Ahora genero el reporte de conexión
                    // $this->resultado[$key]["ConReport"]=[
                    //     "Maquinas"=>[],
                    //     "Modelos"=>[],
                    //     "Total"=>0
                    // ];

                    $reporteTiempo=[
                        "Maquinas"=>[],
                        "Modelos"=>[],
                        "Total"=>0
                    ];

                    //Recorro buscando los resultados
                    foreach($this->tiemposConexion as $fecha=>$estudios){
                        //Consulto si existe este estudio este día
                        if(array_key_exists($elemento["Id"], $estudios)){
                            $tiempo=$estudios[$elemento["Id"]];
                            
                            //Comienzo a procesar, inicio sumando el tiempo
                            $reporteTiempo["Total"]+=$tiempo["Total"];

                            //Ahora recorro las máquinas
                            foreach($tiempo["Maquinas"] as $firmware=>$maquina){
                                //Analizo si existe, si no la creo
                                if(!array_key_exists($firmware,$reporteTiempo["Maquinas"])){
                                    $reporteTiempo["Maquinas"][$firmware]=0;
                                }

                                //Le sumo el total
                                $reporteTiempo["Maquinas"][$firmware]+=$maquina["Total"];

                                //Ahora recorro las modelos
                                foreach($maquina["Modelos"] as $nombre=>$modeltime){
                                    // ✅ Verifico correctamente con el nombre del modelo
                                    if(!array_key_exists($nombre, $reporteTiempo["Modelos"])){
                                        $reporteTiempo["Modelos"][$nombre] = [
                                            "Maquinas" => [],
                                            "Total" => 0
                                        ];
                                    }

                                    // Sumo el tiempo al modelo
                                    $reporteTiempo["Modelos"][$nombre]["Total"] += $modeltime;

                                    // Verifico si la máquina está registrada dentro del modelo
                                    if(!array_key_exists($firmware, $reporteTiempo["Modelos"][$nombre]["Maquinas"])){
                                        $reporteTiempo["Modelos"][$nombre]["Maquinas"][$firmware] = 0;
                                    }

                                    // Sumo el tiempo de esta máquina a este modelo
                                    $reporteTiempo["Modelos"][$nombre]["Maquinas"][$firmware] += $modeltime;
                                }
                            }
                        }
                    }

                    $this->resultado[$key]["ConReport"]=$reporteTiempo;
                }
            }


            Cache::forget("reportResult_" . Auth::user()->id);
        }
    }

    public function verReporte($id)
    {

        if(count($this->resultado)>$id){
            $datos=[
                "Variable"=>"reportResult_" . Auth::user()->id,
                "Id"=>$id,
            ];
            $this->dispatch('abrir-reporte', ['url' => route('reporte.pdf'),'data' => json_encode($datos)]);
        }else{
            $this->dispatch('mostrarToast', 'Continuar reporte', "El reporte no es válido o no existe");
        }

        

    }

    public function enviarCorreo($id){

        if(count($this->resultado)>$id){
            $datos=$this->resultado[$id];

            $pdfResponse = generateReportPDF($id);

            if ($pdfResponse==False) {
                $this->dispatch('mostrarToast', 'Enviar reporte a manager', "No se pudo enviar el correo, el reporte no es válido o no existe");
                return;
            }

            if($datos["Email"]=="" | !preg_match('/^[\w\.\-]+@([\w\-]+\.)+[a-zA-Z]{2,7}$/', $datos["Email"])){
                $this->dispatch('mostrarToast', 'Enviar reporte a manager', "El correo no es válido o está vacío");
                return;
            }

            //Genero la informacion
            $infoReply=array_diff_key($datos, array_flip(['DetailedReport', 'ResultsReport']));
        
            Mail::to($datos["Email"])->send(new EnviarReporte("Reporte por periodo", $pdfResponse,'administracion@coolsofttechnology.com',$infoReply ));
        
            $this->dispatch('mostrarToast', 'Continuar reporte', "Mail enviado");

        }else{
            $this->dispatch('mostrarToast', 'Continuar reporte', "El reporte no es válido o no existe");
        }
    }

    public function enviarCorreoCustom($id){
        if(count($this->resultado)>$id){
            $datos=$this->resultado[$id];

            if( $datos["CustomMail"]=="" | !preg_match('/^[\w\.\-]+@([\w\-]+\.)+[a-zA-Z]{2,7}$/', $datos["CustomMail"])){
                $this->dispatch('mostrarToast', 'Enviar reporte', "El correo personalizado no es válido o está vacío");
                return;
            }

            $pdfResponse = generateReportPDF($id);

            if ($pdfResponse==False) {
                $this->dispatch('mostrarToast', 'Enviar reporte', "Ha ocurrido un error y no se ha podido enviar el correo");
                return;
            }

            //Genero la informacion
            $infoReply=array_diff_key($datos, array_flip(['DetailedReport', 'ResultsReport']));
        
            Mail::to($datos["CustomMail"])->send(new EnviarReporte("Reporte por periodo", $pdfResponse,'administracion@coolsofttechnology.com',$infoReply ));
        
            $this->dispatch('mostrarToast', 'Enviar reporte', "Correo enviado");

        }else{
            $this->dispatch('mostrarToast', 'Enviar reporte', "El reporte no es válido o no existe");
        }
    }

    public function render()
    {
        return view('livewire.estudios.reporte');
    }
}
