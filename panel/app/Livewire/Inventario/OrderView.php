<?php

namespace App\Livewire\Inventario;

use App\Models\Courier;
use App\Models\Product;
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
            $this->dispatch('mostrarToast', 'Registrar alistamiento', 'Alistamiento completado');
            $this->preparando=false;
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
