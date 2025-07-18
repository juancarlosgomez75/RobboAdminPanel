<?php

namespace App\Livewire\Estudios;

use App\Models\BusinessModelHistory;
use App\Models\MachineHistory;
use App\Models\PendingStudy;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Validator;

use Carbon\Carbon;
use Illuminate\Bus\BusServiceProvider;

class Viewedit extends Component

{
    use WithFileUploads;

    public $csv_file;

    public $activo;
    public $editing=false;

    public $habilitado=true;

    public $informacion;
    public $managers;
    public $maquinas;
    public $ciudades;

    public $modelos=[];

    public $historialCompra;

    public $fechaNuevoModelo="";


    //Variables
    public $nombre="";
    public $razonsocial="";
    public $nit=0;
    public $idciudad=0;
    public $direccion="";
    public $responsable="";
    public $telcontacto="";
    public $telcontacto2="";
    public $email="";

    public $moveFirmwareId="";

    public $estudioMoveInfo;

    public $ordenarModelosPor = "user";
    public $ordenarModelosDesc = true;

    public $loadedModels=[];
    public $loadedResults=[];

    public function ordenarModelosBy($filtro){
        //Analizo si cambia es la columna o la dirección
        if($filtro == "manager" || $filtro == "user"){
            if($filtro != $this->ordenarModelosPor){
                $this->ordenarModelosPor = $filtro;
                $this->ordenarModelosDesc = true;
            }else{
                $this->ordenarModelosDesc = !$this->ordenarModelosDesc;
            }
        }
    }
    public function importCsv(){
        //Reinicio los registros

        $this->loadedModels=[];
        $this->loadedResults=[];

        // Validación manual del archivo
        $validator = Validator::make(
            ['csv_file' => $this->csv_file],
            ['csv_file' => 'required|file|mimes:csv,txt']
        );

        if ($validator->fails()) {
            $this->dispatch('mostrarToast', 'Cargar Información', "El archivo debe ser un CSV válido");
            return;
        }

        // Obtener la ruta temporal al archivo subido
        $realPath = $this->csv_file->getRealPath();

        if (!$realPath || !file_exists($realPath)) {
            $this->dispatch('mostrarToast', 'Cargar Información', "No se pudo acceder al archivo CSV.");
            return;
        }

        // Leer contenido del CSV
        $data = array_map('str_getcsv', file($realPath));
        if (empty($data) || count($data) < 2) {
            $this->dispatch('mostrarToast', 'Cargar Información', "El archivo CSV está vacío o no tiene datos válidos.");
            return;
        }

        // Obtener encabezados
        $headers = array_map('trim', array_shift($data));

        $requiredColumns = [
            'USERNAME', 'CUSTOM_NAME', 'USE_CUSTOM', 'CHATURBATE', 'CAMSODA', 'STRIPCHAT',
            'EPLAY', 'AMATEUR', 'CAMS', 'STREAMATE', 'BONGA', 'MANYVIDS', 'F4F', 'XLOVE',
            'CAM4', 'MYFC'
        ];

        // Validar columnas
        if (array_diff($requiredColumns, $headers)) {
            $this->dispatch('mostrarToast', 'Cargar Información', "El archivo no contiene las columnas necesarias.");
            return;
        }

        if (count($requiredColumns) !== count($headers)) {
            $this->dispatch('mostrarToast', 'Cargar Información', "Las columnas no coinciden exactamente.");
            return;
        }

        // Procesar filas
        foreach ($data as $rowIndex => $row) {
            if (count($row) !== count($headers)) {
                continue;
            }

            $rowData = array_combine($headers, $row);

            // Procesamiento personalizado aquí (guardar, validar, etc.)
            logger($rowData);

                // Analizo y proceso
                //Saber si uso custom o no
                $useCustom=True;

                $modelInfo = [
                    "Username"    => strtolower(trim($rowData["USERNAME"])),
                    "Customname"  => strtolower(trim($rowData["CUSTOM_NAME"])),
                    "UseCustom"   => $useCustom,
                    "Pages"       => []
                ];

                // Páginas disponibles (las que quieres revisar si están vacías o no)
                $pageFields = [
                    'CHATURBATE', 'CAMSODA', 'STRIPCHAT',
                    'EPLAY', 'AMATEUR', 'CAMS', 'STREAMATE', 'BONGA', 'MANYVIDS', 'F4F', 'XLOVE',
                    'CAM4', 'MYFC'
                ];

                // Solo incluir páginas no vacías
                foreach ($pageFields as $page) {
                    if (!empty($rowData[$page])) {
                        $modelInfo["Pages"][$page] = strtolower(trim($rowData[$page]));
                    }
                }

                //Almaceno
                $this->loadedModels[]=$modelInfo;
        }

        // Éxito
        // $this->dispatch('mostrarToast', 'Cargar Información', "Se ha cargado el CSV correctamente.");
        $this->dispatch('abrirModalSave');
    }

    public function guardarModelos(){
        //Pregunto que no esté vacío
        if(empty($this->loadedModels)){
            $this->dispatch('mostrarToast', 'Guardar modelos', "No hay modelos ingresadas");
            return;
        }

        //Genero un listado de resultados
        $resultados=[];

        //Pregunto si los managers no existes
        if(count($this->managers)==0){
            $this->dispatch('mostrarToast', 'Guardar modelos', "No hay managers registrados");
            return;
        }

        //Selecciono al manager
        $managerSeleccionado=$this->managers[0];

        foreach($this->loadedModels as $modelo){
            $paginas=[];

            //Almaceno las páginas
            foreach($modelo["Pages"] as $pagina=>$nickname){
                $paginas[]=[
                    "NickName"=>$nickname,
                    "NickPage"=>$pagina
                ];
            }

            //Cargo la data
            $enviar=[
                'Branch' => 'Server',
                'Service' => 'SelfModels',
                'Action' => 'Create',
                "Data"=>[
                    "UserId"=>"1",
                    "ModelData"=>[
                        "ModelUserName"=>$modelo["Username"],
                        "ModelPersonalCm"=>$modelo["UseCustom"],
                        "ModelPersonalCmName"=>$modelo["Customname"],
                        "ModelActive"=>True,
                        "ModelPages"=>$paginas
                    ],
                    "UserData"=>[
                        "Id"=>$managerSeleccionado["Id"]
                    ]
                ],
            ];

            // dd($enviar);
            //Intento enviarlo

            $data=sendBack($enviar);
            if (isset($data['Status'])){
                if($data['Status']){
                    $resultados[$modelo["Username"]]=[
                        "Valor"=>True,
                        "Observaciones"=>""
                    ];
                    continue;
                }

                $resultados[$modelo["Username"]]=[
                    "Valor"=>False,
                    "Observaciones"=>$data['Error'] ?? "Se reporta un error en la consulta"
                ];
                continue;

            }

            $resultados[$modelo["Username"]]=[
                "Valor"=>False,
                "Observaciones"=>"API no retorna correctamente"
            ];

        }

        //Almaceno los resultados
        $this->loadedResults=$resultados;

        //Genero el log
        registrarLog("Producción","Modelos","Crear por CSV","Se ha registrado a los modelos: ".json_encode($this->loadedModels)." y se obtuvieron los siguientes resultados: ".json_encode($this->loadedResults),true);

        //Envio a que se abra el modal
        $this->dispatch('abrirModalResults');
    }


    public function validar(){

        if(Auth::user()->rank < 4){
            $this->dispatch('mostrarToast', 'Editar estudio', 'Error: No tienes los permisos para ejecutar esta acción');
            return false;
        }

        if(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->nombre) && !empty(trim($this->nombre)))){

            $this->dispatch('mostrarToast', 'Editar estudio', "Alerta: El nombre del estudio no es válido");

            return false;
        }
        elseif(!(preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s&]+$/', $this->razonsocial) && !empty(trim($this->razonsocial)))){
            $this->dispatch('mostrarToast', 'Editar estudio', "Alerta: La razón social no es válida");

            return false;
        }
        elseif(!(is_numeric($this->nit) && $this->nit > 0)){

            $this->dispatch('mostrarToast', 'Editar estudio', "Alerta: El NIT no es válido");

            return false;
        }
        elseif(!(is_numeric($this->idciudad) && $this->idciudad > 0)){

            $this->dispatch('mostrarToast', 'Editar estudio', "Alerta: La ciudad no es válida");

            return false;
        }
        elseif(!(preg_match('/^[a-zA-Z0-9#\-. áéíóúÁÉÍÓÚüÜñÑ]+$/', $this->direccion) && !empty(trim($this->direccion)))){

            $this->dispatch('mostrarToast', 'Editar estudio', "Alerta: La dirección no es válida");

            return false;
        }
        elseif(!(preg_match('/^[a-zA-ZÀ-ÿ0-9#\-.\s]+$/', $this->responsable) && !empty(trim($this->responsable)))){
            $this->dispatch('mostrarToast', 'Editar estudio', "Alerta: El nombre del responsable no es válido");

            return false;
        }
        elseif(!(preg_match('/^\+?\d{1,3}?\(?\d{2,4}\)?\d{6,10}$/', $this->telcontacto) && !empty(trim($this->telcontacto)))){
            $this->dispatch('mostrarToast', 'Editar estudio', "Alerta: El número de contacto no es válido");

            return false;
        }
        elseif (!(preg_match('/^\+?\d{1,3}?\(?\d{2,4}\)?\d{6,10}$/', $this->telcontacto) &&
        (empty(trim($this->telcontacto2)) || $this->telcontacto2 === "0" || preg_match('/^\+?\d{1,3}?\(?\d{2,4}\)?\d{6,10}$/', $this->telcontacto2)))) {

            $this->dispatch('mostrarToast', 'Editar estudio', "Alerta: El número de contacto secundario no es válido");

            return false;
        }
        elseif (!empty(trim($this->email))) {
            $emails = explode(';', $this->email);
            $regex = '/^[\w\.-]+@[\w\.-]+\.\w{2,}$/';
            foreach ($emails as $email) {
                $email = trim($email);
                if (!preg_match($regex, $email)) {
                    $this->dispatch('mostrarToast', 'Crear manager', "Alerta: El email '$email' no es válido");
                    return false;
                }
            }
        }

        //Valido la ciudad
        $encontrado=false;
        //Ahora analizo si no está en las ciudades que tengo
        foreach($this->ciudades as $ciudad){
            if($ciudad["Id"]==$this->idciudad){
                $encontrado=true;
                break;
            }
        }

        if($encontrado){
            return true;
        }
        else{
            $this->dispatch('mostrarToast', 'Editar estudio', "Alerta: La ciudad no se reconoce");

            return false;
        }


    }

    public function modificar()
    {
        if($this->validar()){

            // //Genero la petición de informacion
            // $response = Http::withHeaders([
            //     'Authorization' => 'AAAA'
            // ])->withOptions([
            //     'verify' => false // Desactiva la verificación SSL
            // ])->post(config('app.API_URL'), $apidata);

            // $data = $response->json();

            $apidata=[
                'Branch' => 'Server',
                'Service' => 'PlatformUser',
                'Action' => 'CreateUpdateStudy',
                'DataStudy' => [
                    "Id"=>$this->informacion["Id"],
                    "StudyName"=>$this->nombre,
                    "RazonSocial"=>$this->razonsocial,
                    "Nit"=>$this->nit,
                    "CityId"=>$this->idciudad,
                    "Address"=>$this->direccion,
                    "Contact"=>$this->responsable,
                    "Phone"=>$this->telcontacto,
                    "Phone2"=>$this->telcontacto2,
                    "Email"=>$this->email,
                    "Active"=>$this->activo
                ],
                "Data"=>[
                    "UserId"=>"1"
                ]
                ];
            $data=sendBack($apidata);

            if (isset($data['Status'])) {
                if($data['Status']){
                    //$this->resetExcept('ciudades');
                    $this->editing=false;
                    $this->dispatch('mostrarToast', 'Editar estudio', "Se ha modificado al estudio correctamente");

                    registrarLog("Producción","Estudios","Editar","Se ha modificado al estudio #".$this->informacion["Id"].", detalles: ".json_encode($apidata));

                    return;
                }else{

                    $this->dispatch('mostrarToast', 'Editar estudio', "Ha ocurrido un error durante la operación: ".($data['Error']??"Error no reportado"));

                    registrarLog("Producción","Estudios","Editar","Se ha intentado modificar al estudio #".$this->informacion["Id"].", detalles: ".json_encode($apidata),false);
                    return;
                }
            }

            $this->dispatch('mostrarToast', 'Editar estudio', "Ha ocurrido un error durante la operación, contacte a soporte");



        }

    }

    public function moveMachine(){
        //Valido el id
        if($this->moveFirmwareId<100000 || $this->moveFirmwareId > 999999 || !is_numeric($this->moveFirmwareId)){

            $this->dispatch('mostrarToast', 'Mover máquina', "Alerta: El firmware Id no es válido");

            return false;
        }

        //Analizo si ya está
        foreach($this->maquinas as $maq){
            if($maq["FirmwareID"]==$this->moveFirmwareId){
                $this->dispatch('mostrarToast', 'Mover máquina', "Alerta: Esta máquina ya está en este estudio");

                return false;
            }
        }

        //Consulto la máquina
        $data_send=[
            'Branch' => 'Server',
            'Service' => 'Machines',
            'Action' => 'OneView',
            'Data' => [
                "UserId" => "1",
                "Machines"=>[
                    ["FirmwareID"=>$this->moveFirmwareId]
                ]
            ]
        ];
        $data=sendBack($data_send);

        if(isset($data['Status'])){
            if($data["Status"]){
                $this->estudioMoveInfo=$data["Data"]["Machines"][0]["StudyData"];
                //Genero la confirmación
                $this->dispatch('abrirModalMove');
                return;
            }

        }

        $this->dispatch('mostrarToast', 'Mover máquina', "Alerta: No se ha localizado máquinas con este FirmwareID");

        return false;
    }

    public function confirmarVinculacion(){
        $apiData=[
            'Branch' => 'Server',
            'Service' => 'Machines',
            'Action' => 'Assign',
            "Data"=>[
                "UserId"=>"1",
                "Machines"=>[
                    ["FirmwareID"=>$this->moveFirmwareId]
                ]
                ],
            'DataStudy' => [
                "Id"=>$this->informacion["Id"],
            ]
            ];

        $data=sendBack($apiData);

        if (isset($data['Status'])) {
            if($data['Status']){

                $this->dispatch('mostrarToast', 'Mover máquina', "Se ha vinculado la máquina #".$this->moveFirmwareId." con este estudio correctamente");

                registrarLog("Producción","Estudios","Vincular","Se ha movido la máquina #".$this->moveFirmwareId." al estudio #".$this->informacion["Id"],true);

                //Genero la información
                $data_send=[
                    'Branch' => 'Server',
                    'Service' => 'Machines',
                    'Action' => 'OneView',
                    'Data' => [
                        "UserId" => "1",
                        "Machines"=>[
                            ["FirmwareID"=>$this->moveFirmwareId]
                        ]
                    ]
                ];
                $data=sendBack($data_send);

                if($data["Status"]){
                    $Maquina=$data["Data"]["Machines"][0];

                    if(session('API_used',"development")=="production"){
                        //Genero el movimiento
                        $movimiento=new MachineHistory();

                        //Cargo la info
                        $movimiento->machine_id=$Maquina["ID"];
                        $movimiento->description="Vincular";
                        $movimiento->details="Se ha vinculado la máquina con firmware #".$Maquina["FirmwareID"]." al estudio #".$this->informacion["Id"];
                        $movimiento->author=Auth::user()->id;

                        $movimiento->save();
                    }


                }


                $this->moveFirmwareId="";

                $data_send=[
                    'Branch' => 'Server',
                    'Service' => 'PlatformUser',
                    'Action' => 'StudyInfo',
                    'Data' => ["UserId" => "1"],
                    "DataStudy"=>["Id"=>$this->informacion["Id"]]
                ];
                $dataStudio=sendBack($data_send);

                if (isset($dataStudio['Status'])){
                    if($dataStudio['Status']){
                        $this->maquinas=$dataStudio["Data"]["Machines"];
                    }
                }

            }else{

                $this->dispatch('mostrarToast', 'Mover máquina', "Ha ocurrido un error durante la operación: ".($data['Error']??"Error no reportado"));
                registrarLog("Producción","Estudios","Vincular","Se ha intentado mover la máquina #".$this->moveFirmwareId." al estudio #".$this->informacion["Id"].", los datos fueron: ".json_encode($apiData),false);
            }
        }
    }

    public function desvincular($index){
        //Analizo si la id cumple
        if($this->informacion["Id"]!=1){
            //Obtengo la maquina por el index
            $maquina=$this->maquinas[$index];

            $apiData=[
                'Branch' => 'Server',
                'Service' => 'Machines',
                'Action' => 'Assign',
                "Data"=>[
                    "UserId"=>"1",
                    "Machines"=>[
                        ["ID"=>$maquina["ID"]]
                    ]
                    ],
                'DataStudy' => [
                    "Id"=>"1",
                ]
                ];



            // //Genero la petición de informacion
            // $response = Http::withHeaders([
            //     'Authorization' => 'AAAA'
            // ])->withOptions([
            //     'verify' => false // Desactiva la verificación SSL
            // ])->post(config('app.API_URL'), $apiData);

            // $data = $response->json();

            $data=sendBack($apiData);

            if (isset($data['Status'])) {
                if($data['Status']){

                    $this->dispatch('mostrarToast', 'Desvincular máquina', "Se ha desvinculado la máquina #".$maquina["ID"]." de este estudio correctamente");
                    registrarLog("Producción","Estudios","Desvincular","Se ha desvinculado la máquina #".$maquina["ID"]." del estudio #".$this->informacion["Id"],true);

                    unset($this->maquinas[$index]); // Elimina el elemento del array
                    $this->maquinas = array_values($this->maquinas); // Reorganiza los índices

                    if(session('API_used',"development")=="production"){
                        //Genero el movimiento
                        $movimiento=new MachineHistory();

                        //Cargo la info
                        $movimiento->machine_id=$maquina["ID"];
                        $movimiento->description="Desvincular";
                        $movimiento->details="Se ha desvinculado la máquina con firmware #".$maquina["FirmwareID"]." del estudio #".$this->informacion["Id"];
                        $movimiento->author=Auth::user()->id;

                        $movimiento->save();
                    }




                }else{


                    $this->dispatch('mostrarToast', 'Desvincular máquina', "Ha ocurrido un error durante la operación: ".($data['Error']??"Error no reportado"));

                    registrarLog("Producción","Estudios","Desvincular","Se ha intentado desvincular la máquina #".$maquina["ID"]." del estudio #".$this->informacion["Id"].", los datos fueron: ".json_encode($apiData),false);
                }
            }
        }

    }

    public function desactivarEstudio(){
        if(Auth::user()->rank < 4){
            $this->dispatch('mostrarToast', 'Desactivar estudio', 'Error: No tienes los permisos para ejecutar esta acción');
            return false;
        }

        $information=$this->informacion;
        $information["Active"]=false;

        $data_send=[
            'Branch' => 'Server',
            'Service' => 'PlatformUser',
            'Action' => 'CreateUpdateStudy',
            'Data' => ["UserId" => "1"],
            "DataStudy"=>$information,
        ];
        $data=sendBack($data_send);

        if (isset($data['Status'])){
            if($data['Status']){
                $this->activo=False;
                $this->dispatch('mostrarToast', 'Desactivar estudio', "Se ha desactivado el estudio correctamente");
                $this->informacion["Active"]=$information["Active"];
                registrarLog("Producción","Estudios","Desactivar estudio","Se ha desactivado el estudio #".$this->informacion["Id"],true);
            }else{
                $this->dispatch('mostrarToast', 'Desactivar estudio', "Error al desactivar estudio, contacte a soporte");
                registrarLog("Producción","Estudios","Desactivar estudio","Se ha intentado desactivar el estudio #".$this->informacion["Id"],false);
            }
        }
    }

    public function activarEstudio(){

        if(Auth::user()->rank < 4){
            $this->dispatch('mostrarToast', 'Activar estudio', 'Error: No tienes los permisos para ejecutar esta acción');
            return false;
        }

        $information=$this->informacion;
        $information["Active"]=true;

        $data_send=[
            'Branch' => 'Server',
            'Service' => 'PlatformUser',
            'Action' => 'CreateUpdateStudy',
            'Data' => ["UserId" => "1"],
            "DataStudy"=>$information,
        ];
        $data=sendBack($data_send);

        if (isset($data['Status'])){
            if($data['Status']){
                $this->activo=True;
                $this->dispatch('mostrarToast', 'Activar estudio', "Se ha activado el estudio correctamente");
                $this->informacion["Active"]=$information["Active"];
                registrarLog("Producción","Estudios","Activar estudio","Se ha activado el estudio #".$this->informacion["Id"],true);
            }else{
                $this->dispatch('mostrarToast', 'Activar estudio', "Error al activar estudio, contacte a soporte");
                registrarLog("Producción","Estudios","Activar estudio","Se ha intentado activar el estudio #".$this->informacion["Id"],false);
            }
        }
    }

    public function habilitarEstudio(){
        if(Auth::user()->rank < 4){
            $this->dispatch('mostrarToast', 'Habilitar estudio', 'Error: No tienes los permisos para ejecutar esta acción');
            return false;
        }

        $information=$this->informacion;
        $information["Active"]=true;

        $data_send=[
            'Branch' => 'Server',
            'Service' => 'PlatformUser',
            'Action' => 'CreateUpdateStudy',
            'Data' => ["UserId" => "1"],
            "DataStudy"=>$information,
        ];
        $data=sendBack($data_send);

        if (isset($data['Status'])){
            if($data['Status']){
                $this->activo=False;
                $this->dispatch('mostrarToast', 'Habilitar estudio', "Se ha habilitado el estudio correctamente");
                $this->informacion["Active"]=$information["Active"];
                registrarLog("Producción","Estudios","Habilitar estudio","Se ha habilitado el estudio #".$this->informacion["Id"],true);
            }else{
                $this->dispatch('mostrarToast', 'Habilitar estudio', "Error al habilitar estudio, contacte a soporte");
                registrarLog("Producción","Estudios","Habilitar estudio","Se ha intentado habilitar el estudio #".$this->informacion["Id"],false);
            }
        }

        //Lo registro
        PendingStudy::where('id_study', $this->informacion["Id"])->delete();
        $this->habilitado=true;
        $this->activo=True;
    }

    public function deshabilitarEstudio(){
        if(Auth::user()->rank < 4){
            $this->dispatch('mostrarToast', 'Deshabilitar estudio', 'Error: No tienes los permisos para ejecutar esta acción');
            return false;
        }

        $information=$this->informacion;
        $information["Active"]=false;

        $data_send=[
            'Branch' => 'Server',
            'Service' => 'PlatformUser',
            'Action' => 'CreateUpdateStudy',
            'Data' => ["UserId" => "1"],
            "DataStudy"=>$information,
        ];
        $data=sendBack($data_send);

        if (isset($data['Status'])){
            if($data['Status']){
                $this->activo=False;
                $this->dispatch('mostrarToast', 'Deshabilitar estudio', "Se ha deshabilitado el estudio correctamente");
                $this->informacion["Active"]=$information["Active"];
                registrarLog("Producción","Estudios","Deshabilitar estudio","Se ha deshabilitado el estudio #".$this->informacion["Id"],true);
            }else{
                $this->dispatch('mostrarToast', 'Deshabilitar estudio', "Error al deshabilitar estudio, contacte a soporte");
                registrarLog("Producción","Estudios","Deshabilitar estudio","Se ha intentado deshabilitar el estudio #".$this->informacion["Id"],false);
            }
        }

        //Lo registro
        $pendiente=new PendingStudy();
        $pendiente->id_study=$this->informacion["Id"];
        $pendiente->author=Auth::user()->id;
        $pendiente->environment=session('API_used',"development");
        $pendiente->save();
        $this->habilitado=false;
        $this->activo=false;
    }

    public function mount($Informacion,$Managers,$Maquinas,$Ciudades){
        $this->informacion=$Informacion;
        $this->managers = $Managers;
        $this->maquinas = $Maquinas;
        $this->ciudades = $Ciudades;


        $this->activo=$Informacion["Active"];
        $this->habilitado=!PendingStudy::where("id_study", "=", $Informacion['Id'])->where("environment","=",session('API_used',"development"))->exists();

        $this->buscarHistorialModeloCompras();

        //Cargo la información
        $this->nombre=$this->informacion["StudyName"] ?? "No definido";
        $this->razonsocial=$this->informacion["RazonSocial"] ?? "No definida";
        $this->nit=$this->informacion["Nit"] ?? 0;
        $this->idciudad=$this->informacion["CityId"] ?? 1;
        $this->direccion=$this->informacion["Address"] ?? "No definida";
        $this->responsable=$this->informacion["Contact"] ?? "No definido";
        $this->telcontacto=$this->informacion["Phone"] ?? "0";
        $this->telcontacto2=$this->informacion["Phone2"] ?? "0";
        $this->email=$this->informacion["Email"] ?? "noconfigurado@noconfigurado.com";

        //Cargo las modelos
        foreach($Managers as $Manager){
            //Asigno mis modelos
            $misModelos=[];

            //Recorro
            foreach($Manager["ModelsList"] as $modelo){
                $modelo["manager_id"]=$Manager["Id"];
                $modelo["manager_name"]=$Manager["Name"];
                $misModelos[]=$modelo;
            }



            $this->modelos=array_merge($this->modelos,$misModelos);
        }

    }

    public function activarEdicion(){
        $this->editing=true;
    }

    public function regOption(){

        if(Auth::user()->rank < 4){
            $this->dispatch('mostrarToast', 'Editar estudio', 'Error: No tienes los permisos para ejecutar esta acción');
            return false;
        }

        //Valido el campo de fecha
        if (empty($this->fechaNuevoModelo) || !Carbon::hasFormat($this->fechaNuevoModelo, 'Y-m-d') ) {
            $this->dispatch('mostrarToast', 'Registrar opción de compra', 'La fecha ingresada no es válida');
            return false;
        }

        //Genero el registro
        $newModel=new BusinessModelHistory();
        $newModel->id_study=$this->informacion['Id'];
        $newModel->start_date=$this->fechaNuevoModelo;
        $newModel->author=Auth::user()->id;
        $newModel->environment=session('API_used',"production");

        //Intento almacenar
        if($newModel->save()){
            $this->dispatch('mostrarToast', 'Registrar opción de compra', 'Se ha registrado el cambio de modelo');
            $this->buscarHistorialModeloCompras();
            $this->fechaNuevoModelo="";

            registrarLog("Producción","Estudios","Registrar modelo de venta","Se ha registrado el modelo: ".json_encode($newModel),true);

        }else{
            $this->dispatch('mostrarToast', 'Registrar opción de compra', 'Error registrando el cambio de modelo');
            registrarLog("Producción","Estudios","Registrar modelo de venta","Se ha intentado registrar el modelo: ".json_encode($newModel),false);
        }
    }

    public function eliminarModeloCompras($id){

        if(Auth::user()->rank < 4){
            $this->dispatch('mostrarToast', 'Editar estudio', 'Error: No tienes los permisos para ejecutar esta acción');
            return false;
        }

        $modelo = BusinessModelHistory::find($id);

        if ($modelo) {
            $modelo->delete();
            $this->dispatch('mostrarToast', 'Eliminar modelo de venta', 'Se ha eliminado el modelo satisfactoriamente');
            registrarLog("Producción","Estudios","Eliminar modelo de venta","Se ha eliminado el modelo: ".json_encode($modelo),true);
            $this->buscarHistorialModeloCompras();
        } else {
            $this->dispatch('mostrarToast', 'Eliminar modelo de venta', 'Error eliminando el modelo');
            registrarLog("Producción","Estudios","Eliminar modelo de venta","Se ha intentado eliminar el modelo: ".json_encode($modelo),false);
        }
    }

    public function buscarHistorialModeloCompras(){
        //Genero la búsqueda
        $this->historialCompra=BusinessModelHistory::where("id_study", "=", $this->informacion['Id'])->where("environment","=",session('API_used',"production"))->get();
    }

    public function render()
    {
        usort($this->maquinas, function ($a, $b) {
            return strcmp($a["FirmwareID"], $b["FirmwareID"]);
        });

        $modelosFiltrados=$this->modelos;

        //Aplico los filtros a los modelos
        if($this->ordenarModelosPor=="manager"){
            if($this->ordenarModelosDesc){
                usort($modelosFiltrados, function ($a, $b) {
                    return strcmp($a["manager_name"], $b["manager_name"]);
                });
            }else{
                usort($modelosFiltrados, function ($a, $b) {
                    return strcmp($b["manager_name"], $a["manager_name"]);
                });
            }
        }else if($this->ordenarModelosPor=="user"){
            if($this->ordenarModelosDesc){
                usort($modelosFiltrados, function ($a, $b) {
                    return strcmp($a["ModelUserName"], $b["ModelUserName"]);
                });
            }else{
                usort($modelosFiltrados, function ($a, $b) {
                    return strcmp($b["ModelUserName"], $a["ModelUserName"]);
                });
            }
        }

        return view('livewire.estudios.viewedit',["informacion"=>$this->informacion, "managers"=> $this->managers,"modelosOrdenados"=>$modelosFiltrados, "maquinas"=> $this->maquinas,"Ciudades"=> $this->ciudades]);
    }
}
