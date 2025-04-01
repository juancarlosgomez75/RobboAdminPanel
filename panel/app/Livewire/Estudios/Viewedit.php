<?php

namespace App\Livewire\Estudios;

use App\Models\MachineHistory;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class Viewedit extends Component

{
    public $editing=false;

    public $informacion;
    public $managers;
    public $maquinas;
    public $ciudades;

    public $modelos=[];


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

    public function validar(){

        if(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->nombre) && !empty(trim($this->nombre)))){

            $this->dispatch('mostrarToast', 'Editar estudio', "Alerta: El nombre del estudio no es válido");

            return false;
        }
        elseif(!(preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->razonsocial) && !empty(trim($this->razonsocial)))){
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
        elseif(!(preg_match('/^[\w\.-]+@[\w\.-]+\.\w{2,}$/', $this->email) && !empty(trim($this->email)))){
            $this->dispatch('mostrarToast', 'Editar estudio', "Alerta: El email no es válido");

            return false;
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
                    "Email"=>$this->email
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

                    //Genero el movimiento
                    $movimiento=new MachineHistory();

                    //Cargo la info
                    $movimiento->machine_id=$Maquina["ID"];
                    $movimiento->description="Vincular";
                    $movimiento->details="Se ha vinculado la máquina con firmware #".$Maquina["FirmwareID"]." al estudio #".$this->informacion["Id"];
                    $movimiento->author=Auth::user()->id;

                    $movimiento->save();
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


                    //Genero el movimiento
                    $movimiento=new MachineHistory();

                    //Cargo la info
                    $movimiento->machine_id=$maquina["ID"];
                    $movimiento->description="Desvincular";
                    $movimiento->details="Se ha desvinculado la máquina con firmware #".$maquina["FirmwareID"]." del estudio #".$this->informacion["Id"];
                    $movimiento->author=Auth::user()->id;

                    $movimiento->save();


    
                }else{


                    $this->dispatch('mostrarToast', 'Desvincular máquina', "Ha ocurrido un error durante la operación: ".($data['Error']??"Error no reportado"));

                    registrarLog("Producción","Estudios","Desvincular","Se ha intentado desvincular la máquina #".$maquina["ID"]." del estudio #".$this->informacion["Id"].", los datos fueron: ".json_encode($apiData),false);
                }
            }
        }

    }

    public function mount($Informacion,$Managers,$Maquinas,$Ciudades){
        $this->informacion=$Informacion;
        $this->managers = $Managers;
        $this->maquinas = $Maquinas;
        $this->ciudades = $Ciudades;

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
            }

            $misModelos[]=$modelo;

            $this->modelos=array_merge($this->modelos,$misModelos);
        }
        
    }

    public function activarEdicion(){
        $this->editing=true;
    }
    
    public function render()
    {
        return view('livewire.estudios.viewedit',["informacion"=>$this->informacion, "managers"=> $this->managers,"modelos"=>$this->modelos, "maquinas"=> $this->maquinas,"Ciudades"=> $this->ciudades]);
    }
}
