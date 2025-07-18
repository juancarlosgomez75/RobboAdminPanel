<?php

namespace App\Livewire\Inventario;

use App\Models\Courier;
use App\Models\MachineHistory;
use App\Models\Product;
use App\Models\ProductInventoryMovement;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isNumeric;

class OrderView extends Component
{
    public $orden;

    public $preparando=false;
    public $preparacion_list=[];
    public $details="";

    public $enviando=false;
    public $mensajerias;

    public $courier_enterprise="0";
    public $tracking_code="";

    public $reasonCancel="";
    public $recibirNotas="";

    public $facNumber="";

    public function iniciarAlistamiento(){
        //Actualizo el estado
        $this->preparando=true;
        $this->preparacion_list=[];

        //Genero el listado
        foreach(json_decode($this->orden->creation_list) as $producto){
            //Si usa firmware debo crear un elemento por acada id
            if ($producto->use_firmware){
                for($i = 0; $i < $producto->amount; $i++){
                    $this->preparacion_list[]=[
                        "check"=>False,
                        "id"=>$producto->id,
                        "name"=>$producto->name,
                        "amount"=>1,
                        "use_firmware"=> $producto->use_firmware,
                        "firmwareid"=>""
                    ];
                }
            }else{
                $this->preparacion_list[]=[
                    "check"=>False,
                    "id"=>$producto->id,
                    "name"=>$producto->name,
                    "amount"=>$producto->amount,
                    "use_firmware"=> $producto->use_firmware
                ];
            }
        }
    }

    public function completarAlistamiento(){
        //Ahora valido las observaciones
        if (!empty(trim($this->details)) && preg_match('/<[^>]*>/', $this->details)){
            $this->dispatch('mostrarToast', 'Registrar alistamiento', 'Error: Las observaciones no son válidas o el campo está vacío');
            return false;
        }

        //Ahora valido el check y el firmware
        $firmwares=[];
        foreach($this->preparacion_list as $index=>$item){
            //Analizo si está check
            if(!$item["check"]){
                $this->dispatch('mostrarToast', 'Registrar alistamiento', 'Error: El producto #'.($index+1).' no ha sido marcado como completado');
                return false;
            }

            //Analizo si usa firmware
            if($item["use_firmware"]){
                #Consultto si el firmware no está
                if(in_array($item["firmwareid"],$firmwares)){
                    $this->dispatch('mostrarToast', 'Registrar alistamiento', 'Error: La id de firmware del producto #'.($index+1).' ya ha sido ingresada anteriormente');
                    return false;
                }
                $firmwares[]=$item["firmwareid"];
                if($item["firmwareid"]<"100000" || $item["firmwareid"]>"999999"){
                    $this->dispatch('mostrarToast', 'Registrar alistamiento', 'Error: La id de firmware del producto #'.($index+1).' no está en el rango correcto');
                    return false;
                }
            }
        }

        //Edito la información
        $this->orden->prepared_by=Auth::id();
        $this->orden->preparation_list=json_encode($this->preparacion_list);
        $this->orden->preparation_date=now();
        $this->orden->preparation_notes=strip_tags($this->details);

        $this->orden->status="prepared";

        //Si almaceno
        if($this->orden->save()){

            registrarLog("Inventario","Órdenes","Alistar","Ha completado el alistamiento de la orden # ".$this->orden->id,true);

            //Genero los descargos
            foreach($this->preparacion_list as $element){
                //Obtengo el producto
                $pto=Product::find($element["id"]);

                //Siempre verifico todo
                if($pto){

                    //Ahora actualizo si usa firmware o no
                    if($element["use_firmware"] && $this->orden->study_id!=null){



                        $apiData=[
                            'Branch' => 'Server',
                            'Service' => 'Machines',
                            'Action' => 'Assign',
                            "Data"=>[
                                "UserId"=>"1",
                                "Machines"=>[
                                    ["FirmwareID"=>$element["firmwareid"]]
                                ]
                                ],
                            'DataStudy' => [
                                "Id"=>($this->orden->type=="shipping") ? $this->orden->study_id : "1",
                            ]
                        ];

                        $data=sendBack($apiData,"AAA",true);

                        if (!isset($data['Status'])) {
                            // $this->dispatch('mostrarToast', 'Mover máquina', 'Se ha generado un error al mover automáticamente una máquina, contacte a soporte');
                            registrarLog("Producción","Estudios","Vincular","Error al mover la máquina Firmware#".$element["firmwareid"]." al estudio #".$this->orden->study_id.", resultado de operación de la orden #".$this->orden->id,false);
                        }
                        elseif(!$data['Status']){
                            // $this->dispatch('mostrarToast', 'Mover máquina', 'No se ha completado con éxito, contacte a soporte');
                            registrarLog("Producción","Estudios","Vincular","Error al mover la máquina Firmware#".$element["firmwareid"]." al estudio #".$this->orden->study_id.", resultado de operación de la orden #".$this->orden->id,false);
                        }else{
                            registrarLog("Producción","Estudios","Vincular","Se ha movido la máquina Firmware#".$element["firmwareid"]." al estudio #".$this->orden->study_id.", resultado de operación de la orden #".$this->orden->id,true);
                        }

                        //Genero la información
                        $data_send=[
                            'Branch' => 'Server',
                            'Service' => 'Machines',
                            'Action' => 'OneView',
                            'Data' => [
                                "UserId" => "1",
                                "Machines"=>[
                                    ["FirmwareID"=>$element["firmwareid"]]
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
                            $movimiento->description="Orden";
                            $movimiento->details="Se ha vinculado la máquina con firmware #".$Maquina["FirmwareID"]." al estudio #".$this->orden->study_id. " por la orden #".$this->orden->id;
                            $movimiento->author=Auth::user()->id;

                            $movimiento->save();
                        }

                    }
                }

            }


            $this->dispatch('mostrarToast', 'Registrar alistamiento', 'Alistamiento completado');
            $this->preparando=false;

        }else{
            $this->dispatch('mostrarToast', 'Registrar alistamiento', 'Error completando el alistamiento, contacte con soporte');
            registrarLog("Inventario","Órdenes","Alistar","Ha intentado completar el alistamiento de la orden # ".$this->orden->id,false);
        }
    }

    public function iniciarEnvio(){
        $this->enviando=true;
    }

    public function reportarGuia(){
        //Valido la guia
        if(!(preg_match('/^[a-zA-Z0-9#\-. áéíóúÁÉÍÓÚüÜñÑ]+$/', $this->tracking_code) && !empty(trim($this->tracking_code)))){
            $this->dispatch('mostrarToast', 'Reportar guía', 'La guía no es válida');
            return false;
        }
        elseif(!(Courier::find($this->courier_enterprise))){
            $this->dispatch('mostrarToast', 'Reportar guía', 'La empresa de mensajería no es válida');
            return false;
        }

        //Edito la información
        $this->orden->enlisted_by=Auth::id();
        $this->orden->enlist_date=now();
        $this->orden->tracking=$this->tracking_code;
        $this->orden->enterprise=$this->courier_enterprise;
        $this->orden->status="waiting";

        //Si almaceno
        if($this->orden->save()){
            $this->dispatch('mostrarToast', 'Reportar guía', 'Se ha reportado la guía del envío');
            $this->enviando=false;
            registrarLog("Inventario","Órdenes","Reportar guia","Se ha reportado la guia de la orden # ".$this->orden->id,true);
        }else{
            $this->dispatch('mostrarToast', 'Reportar guía', 'Error reportando la guía, contacta con soporte');
            registrarLog("Inventario","Órdenes","Reportar guia","Se ha intentado reportar la guia de la orden # ".$this->orden->id,false);
        }
    }

    public function completarEnvio(){
        //Edito la información
        $this->orden->sended_by=Auth::id();
        $this->orden->send_date=now();
        $this->orden->status="sended";

        //Si almaceno
        if($this->orden->save()){
            $this->dispatch('mostrarToast', 'Reportar envío', 'Se ha completado el envío');
            $this->enviando=false;
            registrarLog("Inventario","Órdenes","Reportar envio","Se ha enviado la orden #".$this->orden->id,true);
        }else{
            $this->dispatch('mostrarToast', 'Reportar envío', 'Error reportando el envío, contacta a soporte');
            registrarLog("Inventario","Órdenes","Reportar envío","Se ha intentado reportar el envío de la orden # ".$this->orden->id,false);
        }
    }

    public function cancelarOrden(){

        if(Auth::user()->rank < 4){
            $this->dispatch('mostrarToast', 'Cancelar orden', 'Error: No tienes los permisos para ejecutar esta acción');
            return false;
        }

        //Valido la razon
        if(empty(trim($this->reasonCancel)) || preg_match('/<[^>]*>/', $this->reasonCancel)){
            $this->dispatch('mostrarToast', 'Cancelar orden', 'La razón tiene caracteres no válidos o está vacía');
            return false;
        }

        if($this->orden->status=="sended"){
            $this->dispatch('mostrarToast', 'Cancelar orden', 'No se puede cancelar una orden enviada');
            return false;
        }

        //Edito la información
        $this->orden->canceled_by=Auth::id();
        $this->orden->cancel_date=now();
        $this->orden->cancellation_reason=strip_tags($this->reasonCancel);
        $this->orden->status="canceled";

        //Si almaceno
        if($this->orden->save()){
            registrarLog("Inventario","Órdenes","Cancelar orden","Ha cancelado la orden # ".$this->orden->id,true);

            //Devuelvo el stock completo
            foreach(json_decode($this->orden->creation_list) as $element){
                //Busco el producto
                $pto=Product::find($element->id);

                //Genero un nuevo movimiento
                $mov=new ProductInventoryMovement();

                //Almaceno la información
                $mov->inventory_id=$pto->inventory->id;
                $mov->type="income";
                $mov->reason="Cancelación de orden";
                $mov->amount=$element->amount;
                $mov->stock_before=$pto->inventory->stock_available;
                $mov->stock_after=$pto->inventory->stock_available+$element->amount;
                $mov->author=Auth::id();
                $mov->order_id=$this->orden->id;

                //Guardo
                if(!$mov->save()){
                    $this->dispatch('mostrarToast', 'Cancelar orden', 'Se ha generado un error al generar un movimiento, contacte a soporte');
                }

                //Ahora modifico el stock
                $pto->inventory->stock_available=$mov->stock_after;

                if(!$pto->inventory->save()){
                    $this->dispatch('mostrarToast', 'Cancelar orden', 'Se ha generado un error al actualizar stock, contacte a soporte');
                }
            }

            $this->dispatch('mostrarToast', 'Cancelar orden', 'Se ha cancelado la orden');

        }else{
            registrarLog("Inventario","Órdenes","Cancelar orden","Ha intentado cancelar la orden # ".$this->orden->id,false);
            $this->dispatch('mostrarToast', 'Cancelar orden', 'Error cancelando la orden, contacta con soporte');
        }
    }

    public function reportarLlegada(){
        //Analizo si la descripción está bien
        if(!empty(trim($this->recibirNotas)) && preg_match('/<[^>]*>/', $this->recibirNotas)){
            $this->dispatch('mostrarToast', 'Reportar llegada', 'Los comentarios tienen caracteres no válidos');
            return false;
        }

        //Edito la información
        $this->orden->received_by=Auth::id();
        $this->orden->received_date=now();
        $this->orden->received_notes=strip_tags($this->recibirNotas);
        $this->orden->status="collected";

        //Intento almacenar
        //Si almaceno
        if($this->orden->save()){
            registrarLog("Inventario","Órdenes","Recibir orden","Ha recibido la orden # ".$this->orden->id,true);

            //Devuelvo el stock completo
            foreach(json_decode($this->orden->creation_list) as $element){
                //Busco el producto
                $pto=Product::find($element->id);

                //Genero un nuevo movimiento
                $mov=new ProductInventoryMovement();

                //Almaceno la información
                $mov->inventory_id=$pto->inventory->id;
                $mov->type="income";
                $mov->reason="Recepción de orden";
                $mov->amount=$element->amount;
                $mov->stock_before=$pto->inventory->stock_available;
                $mov->stock_after=$pto->inventory->stock_available+$element->amount;
                $mov->author=Auth::id();
                $mov->order_id=$this->orden->id;

                //Guardo
                if(!$mov->save()){
                    $this->dispatch('mostrarToast', 'Recibir orden', 'Se ha generado un error al generar un movimiento, contacte a soporte');
                }

                //Ahora modifico el stock
                $pto->inventory->stock_available=$mov->stock_after;

                if(!$pto->inventory->save()){
                    $this->dispatch('mostrarToast', 'Recibir orden', 'Se ha generado un error al actualizar stock, contacte a soporte');
                }
            }

            $this->dispatch('mostrarToast', 'Recibir orden', 'Se ha recibido la orden');

        }else{
            registrarLog("Inventario","Órdenes","Recibir orden","Ha intentado recibir la orden # ".$this->orden->id,false);
            $this->dispatch('mostrarToast', 'Recibir orden', 'Error recibiendo la orden, contacta con soporte');
        }

        //Ahora reporto la llegada
    }

    public function finalizarOrden(){
        if(Auth::user()->rank < 4){
            $this->dispatch('mostrarToast', 'Terminar orden', 'Error: No tienes los permisos para ejecutar esta acción');
            return false;
        }

        // if($this->facNumber!="" && !is_numeric($this->facNumber)){
        //     $this->dispatch('mostrarToast', 'Terminar orden', 'Error: El código de facturación no es numérico');
        //     return false;
        // }

        //Edito la información
        $this->orden->finished_by=Auth::id();
        $this->orden->finished_date=now();
        $this->orden->finished=True;
        $this->orden->internal_code=$this->facNumber??"";

        //Si almaceno
        if($this->orden->save()){
            registrarLog("Inventario","Órdenes","Finalizar orden","Ha finalizado la orden # ".$this->orden->id,true);

            $this->dispatch('mostrarToast', 'Finalizar orden', 'Se ha finalizado la orden');

        }else{
            registrarLog("Inventario","Órdenes","Finalizar orden","Ha intentado finalizar la orden # ".$this->orden->id,false);
            $this->dispatch('mostrarToast', 'Finalizar orden', 'Error finalizando la orden, contacta con soporte');
        }
    }

    public function mount($orden){
        $this->orden = $orden;

        //Consulto las mensajerias
        $this->mensajerias=Courier::all();
    }

    public function render()
    {
        return view('livewire.inventario.order-view');
    }
}
