<?php

namespace App\Livewire\Inventario;

use App\Models\Courier;
use App\Models\Product;
use App\Models\ProductInventoryMovement;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class OrderView extends Component
{
    public $orden;

    public $preparando=false;
    public $preparacion_list=[];

    public $preparacion_product="0";
    public $preparacion_amount="1";
    public $preparacion_firmware="";
    public $preparacion_count=[];
    public $details="";

    public $enviando=false;
    public $mensajerias;

    public $courier_enterprise="0";
    public $tracking_code="";

    public $reasonCancel="";

    public function removeProduct($index)
    {
        //Disminuyo
        $this->preparacion_count[$this->preparacion_list[$index]["id"]]-= $this->preparacion_list[$index]["amount"];

        unset($this->preparacion_list[$index]); // Elimina el elemento del array
        $this->preparacion_list = array_values($this->preparacion_list); // Reorganiza los índices
        $this->dispatch('mostrarToast', 'Quitar producto', 'Se ha quitado el producto del alistamiento');

    }

    public function preparacionAdd(){
        //Analizo si son valores correctos
        if(!is_numeric($this->preparacion_amount) || $this->preparacion_amount <= 0){
            $this->dispatch('mostrarToast', 'Añadir producto', 'La cantidad ingresada no es válida');
            return;
        }
        elseif($this->preparacion_product== '0'){
            $this->dispatch('mostrarToast', 'Añadir producto', 'Por favor selecciona un producto');
            return;
        }
        elseif($this->preparacion_firmware!="" && (!is_numeric($this->preparacion_firmware) || $this->preparacion_firmware<100000)){
            $this->dispatch('mostrarToast', 'Añadir producto', 'El firmware ingresado no es válido');
            return;
        }

        $encontrado=false;
        foreach (json_decode($this->orden->creation_list,true) as $index=>$pd){
            if($pd["id"]==$this->preparacion_product){
                $encontrado=true;
                break;
            }
        }

        if(!$encontrado){
            $this->dispatch('mostrarToast', 'Añadir producto', 'Error: este producto no pertenece a esta orden');
            return;
        }

        //Busco el producto
        $pto=Product::where("id","=",$this->preparacion_product)->where("available","=","1")->first();

        if(!$pto){
            $this->dispatch('mostrarToast', 'Añadir producto', 'Error: este producto no fue encontrado o no está disponible');
            return;
        }

        //Ahora analizo si puedo añadir esto
        $busc=($this->preparacion_count[$this->preparacion_product]??0)+$this->preparacion_amount;
        if($pto->inventory->stock_available<$busc){
            $this->dispatch('mostrarToast', 'Añadir producto', 'No hay stock suficiente de este producto');
            return;
        }

        //Añade el producto si no existe, si existe le sumo
        if (!array_key_exists($this->preparacion_product, $this->preparacion_count)) {
            $this->preparacion_count[$this->preparacion_product]=$this->preparacion_amount;
        }else{
            $this->preparacion_count[$this->preparacion_product]+=$this->preparacion_amount;
        }
        

        //Creo el array que almaceno
        $informacion=[];

        //Le cargo la informacion
        $informacion["id"]=$this->preparacion_product;
        $informacion["name"]=$pto->name;
        $informacion["amount"]=$this->preparacion_amount;

        if($this->preparacion_firmware!=""){
            $informacion["firmware"]=$this->preparacion_firmware;  
        }

        //Añado
        $this->preparacion_list[]=$informacion;

        $this->dispatch('mostrarToast', 'Añadir producto', 'Producto añadido');

        $this->preparacion_product="0";
        $this->preparacion_amount="1";
        $this->preparacion_firmware="";

    }

    public function iniciarAlistamiento(){
        $this->preparando=true;
    }

    public function completarAlistamiento(){
        //Ahora valido las observaciones
        if (!empty(trim($this->details)) && !preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->details)){
            $this->dispatch('mostrarToast', 'Registrar alistamiento', 'Las observaciones no son válidas');
            return false;
        }

        //Edito la información
        $this->orden->prepared_by=Auth::id();
        $this->orden->preparation_list=json_encode($this->preparacion_list);
        $this->orden->preparation_date=now();
        $this->orden->preparation_notes=$this->details;
        $this->orden->status="prepared";

        //Si almaceno
        if($this->orden->save()){
            //Genero los descargos
            foreach($this->preparacion_list as $element){
                //Obtengo el producto
                $pto=Product::find($element["id"]);

                //Siempre verifico todo
                if($pto){
                    //Genero un nuevo movimiento
                    $mov=new ProductInventoryMovement();

                    //Almaceno la información
                    $mov->inventory_id=$pto->inventory->id;
                    $mov->type="expense";
                    $mov->reason="Alistamiento de orden";
                    $mov->amount=$element["amount"];
                    $mov->stock_before=$pto->inventory->stock_available;
                    $mov->stock_after=$pto->inventory->stock_available-$element["amount"];
                    $mov->author=Auth::id();
                    $mov->order_id=$this->orden->id;

                    //Guardo
                    if(!$mov->save()){
                        $this->dispatch('mostrarToast', 'Reportar movimiento', 'Se ha generado un error, contacte a soporte');
                    }

                    //Ahora modifico el stock
                    $pto->inventory->stock_available=$mov->stock_after;

                    if(!$pto->inventory->save()){
                        $this->dispatch('mostrarToast', 'Reportar stock', 'Se ha generado un error, contacte a soporte');
                    }

                    //Ahora actualizo si es el caso
                    if(isset($element["firmware"]) && $this->orden->study_id!=null){
                        $apiData=[
                            'Branch' => 'Server',
                            'Service' => 'Machines',
                            'Action' => 'Assign',
                            "Data"=>[
                                "UserId"=>"1",
                                "Machines"=>[
                                    ["FirmwareID"=>$element["firmware"]]
                                ]
                                ],
                            'DataStudy' => [
                                "Id"=>$this->orden->study_id,
                            ]
                        ];

                        $data=sendBack($apiData);

                        if (!isset($data['Status'])) {
                            $this->dispatch('mostrarToast', 'Mover máquina', 'Se ha generado un error al mover automáticamente una máquina, contacte a soporte');

                        }
                        elseif(!$data['Status']){
                            $this->dispatch('mostrarToast', 'Mover máquina', 'No se ha completado con éxito, contacte a soporte');
                        }else{
                            registrarLog("Producción","Estudios","Vincular","Se ha movido la máquina Firmware#".$element["firmware"]." al estudio #".$this->orden->study_id.", resultado de operación de la orden #".$mov->id,true);
                        }

                        


                    }
                }

            }


            $this->dispatch('mostrarToast', 'Registrar alistamiento', 'Alistamiento completado');
            $this->preparando=false;

        }else{
            $this->dispatch('mostrarToast', 'Registrar alistamiento', 'Error completando el alistamiento, contacte con soporte');
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
        }else{
            $this->dispatch('mostrarToast', 'Reportar guía', 'Error reportando la guía, contacta con soporte');
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
        }else{
            $this->dispatch('mostrarToast', 'Reportar envío', 'Error reportando el envío, contacta a soporte');
        }
    }

    public function cancelarOrden(){
        //Valido la razon
        if(!(preg_match('/^[a-zA-Z0-9#\-. áéíóúÁÉÍÓÚüÜñÑ]+$/', $this->reasonCancel) && !empty(trim($this->reasonCancel)))){
            $this->dispatch('mostrarToast', 'Cancelar orden', 'La razón tiene caracteres no válidos');
            return false;
        }

        if($this->orden->status=="sended"){
            $this->dispatch('mostrarToast', 'Cancelar orden', 'No se puede cancelar una orden enviada');
            return false;
        }

        //Edito la información
        $this->orden->canceled_by=Auth::id();
        $this->orden->cancel_date=now();
        $this->orden->cancellation_reason=$this->reasonCancel;
        $this->orden->status="canceled";

        //Si almaceno
        if($this->orden->save()){
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
            $this->dispatch('mostrarToast', 'Cancelar orden', 'Error cancelando la orden, contacta con soporte');
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
