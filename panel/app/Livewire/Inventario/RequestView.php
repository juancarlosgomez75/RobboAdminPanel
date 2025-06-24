<?php

namespace App\Livewire\Inventario;

use App\Models\Product;
use App\Models\ProductInventoryMovement;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class RequestView extends Component
{
    public $pedido;

    public $deliveryList=[];
    public $pendientes=[];
    public $entregando=[];
    public $entregaActive=False;
    public $observaciones="";
    public $reasonCancel="";
    public $pendienteInventariar=False;

    public function loadData(){
        //Reinicio la data
        $this->pendientes=[];
        $this->entregando=[];

        //Cargo los pendientes
        foreach(json_decode($this->pedido->creation_list,true) as $producto){
            $this->pendientes[]=$producto["amount"];
            $this->entregando[]=0;
        }

        //Ahorra recorro los delivery
        if(!is_null($this->pedido->delivery_list)){
            foreach(json_decode($this->pedido->delivery_list,true) as $fecha=>$delivery){
                //Ahora recorro los productos
                foreach($delivery["products"] as $index=>$producto){
                    $this->pendientes[$index]=$this->pendientes[$index]-$producto;
                }

                //Ahora analizo si estoy pendiente de inventariar o no
                if(!$delivery["inventoried"]){
                    $this->pendienteInventariar=True;
                }
            }
        }

        $this->deliveryList=json_decode($this->pedido->delivery_list,True);
    }

    public function mount($pedido){
        //Cargo la info
        $this->pedido=$pedido;

        //Cargo la data
        $this->loadData();
    }

    public function iniciarEntrega(){
        $this->entregaActive=True;
    }

    public function completarEntrega(){
        //Recorro los elementos paraa ver si son válidos
        foreach($this->entregando as $index=>$cantidad){
            if($cantidad>$this->pendientes[$index]){
                $this->dispatch('mostrarToast', 'Reportar entrega', "Alerta: La cantidad reportada del producto #".($index+1)." supera a la pendiente");
                return false;
            }
            elseif($cantidad<0 || !is_numeric($cantidad)){
                $this->dispatch('mostrarToast', 'Reportar entrega', "Alerta: La cantidad reportada del producto #".($index+1)." no es válida");
                return false;
            }
        }

        if (!empty(trim($this->observaciones)) && !preg_match('/^[a-zA-Z0-9\/\-_\.\,\$\#\@\!\?\%\&\*\(\)\[\]\{\}\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->observaciones)){
            $this->dispatch('mostrarToast', 'Reportar entrega', 'Las observaciones no son válidas');
            return false;
        }

        //Analizo si es la única entrega para iniciar reporte o no
        if(is_null($this->pedido->delivery_list)){
            $delivery=[];
        }else{
            $delivery=json_decode($this->pedido->delivery_list,True);
        }

        //Genero la estructura
        $delivery[Carbon::now()->toDateTimeString()]=[
            "products"=>$this->entregando,
            "author"=>Auth::id(),
            "inventoried"=>False,
            "inventoried_by"=>null,
            "inventoried_date"=>null,
            "details"=>$this->observaciones
        ];

        //Intento actualizar
        $this->pedido->delivery_list=json_encode($delivery);

        //Analizo si se completó la entrega
        $delCompleted=True;
        foreach($this->entregando as $index=>$cantidad){
            if(($this->pendientes[$index]-$cantidad)>0){
                $delCompleted=False;
                break;
            }
        }

        $this->pedido->status=$delCompleted?"delivered":"partial delivery";

        //Intento guardar
        if($this->pedido->save()){
            $this->dispatch('mostrarToast', 'Reportar entrega', "Se ha completado la entrega");

            //Recargo la data
            $this->loadData();

            //Indico que ya no ando entregando
            $this->entregaActive=False;

            //Reinicio el diccionario de entregando
            registrarLog("Inventario","Pedidos","Reportar entrega","Ha reportado una entrega de la siguiente forma: ".json_encode($delivery),true);

        }else{
            registrarLog("Inventario","Pedidos","Reportar entrega","Ha intentado reportar una entrega de la siguiente forma: ".json_encode($delivery),false);
            $this->dispatch('mostrarToast', 'Reportar entrega', "Error guardando la entrega, contacte a soporte");
        }
    }

    public function cancelarPedido(){
        if(Auth::user()->rank < 4){
            $this->dispatch('mostrarToast', 'Cancelar orden', 'Error: No tienes los permisos para ejecutar esta acción');
            return false;
        }

        //Valido la razon
        if(!(preg_match('/^[a-zA-Z0-9#\-. áéíóúÁÉÍÓÚüÜñÑ]+$/', $this->reasonCancel) && !empty(trim($this->reasonCancel)))){
            $this->dispatch('mostrarToast', 'Cancelar orden', 'La razón tiene caracteres no válidos');
            return false;
        }

        if($this->pedido->status=="sended"){
            $this->dispatch('mostrarToast', 'Cancelar orden', 'No se puede cancelar una orden enviada');
            return false;
        }

        //Edito la información
        $this->pedido->canceled_by=Auth::id();
        $this->pedido->cancel_date=now();
        $this->pedido->cancellation_reason=$this->reasonCancel;
        $this->pedido->status="canceled";

        //Si almaceno
        if($this->pedido->save()){
            $this->dispatch('mostrarToast', 'Cancelar pedido', 'Se ha cancelado el pedido');
            $this->entregaActive=False;

            registrarLog("Inventario","Pedidos","Cancelar pedido","Ha cancelado el pedido con id # : ".$this->pedido->id,true);

        }else{
            registrarLog("Inventario","Pedidos","Cancelar pedido","Ha intentado cancelar el pedido con id # : ".$this->pedido->id,false);
            $this->dispatch('mostrarToast', 'Cancelar pedido', 'Error cancelando el pedido, contacta con soporte');
        }
    }

    public function finalizarOrden(){
        if(Auth::user()->rank < 4){
            $this->dispatch('mostrarToast', 'Cerrar pedido', 'Error: No tienes los permisos para ejecutar esta acción');
            return false;
        }

        //Edito la información
        $this->pedido->finished_by=Auth::id();
        $this->pedido->finished_date=now();
        $this->pedido->finished=True;

        //Si almaceno
        if($this->pedido->save()){
            registrarLog("Inventario","Pedidos","Finalizar orden","Ha finalizado el pedido # ".$this->pedido->id,true);

            $this->dispatch('mostrarToast', 'Finalizar orden', 'Se ha finalizado el pedido');

        }else{
            registrarLog("Inventario","Pedidos","Finalizar orden","Ha intentado finalizar el pedido # ".$this->pedido->id,false);
            $this->dispatch('mostrarToast', 'Finalizar orden', 'Error finalizando el pedido, contacta con soporte');
        }
    }

    public function reportarInventario(){

        if(Auth::user()->rank < 4){
            $this->dispatch('mostrarToast', 'Reportar inventario', 'Error: No tienes los permisos para ejecutar esta acción');
            return false;
        }
        //Analizo si tengo pendientes o no
        if($this->pendienteInventariar){
            //Recorro los delivery list
            foreach(json_decode($this->pedido->delivery_list,true) as $fecha=>$delivery){
                //Ahora analizo si está pendiente el inventariar
                if(!$delivery["inventoried"]){
                    //Recorro los productos registrados
                    foreach(json_decode($this->pedido->creation_list,true) as $index=>$producto){
                        //Analizo si es un producto interno
                        if($producto["internal"]){
                            //Busco el producto
                            $pto=Product::find($producto['id']);

                            //Genero un nuevo movimiento
                            $mov=new ProductInventoryMovement();

                            //Almaceno la información
                            $mov->inventory_id=$pto->inventory->id;

                            $mov->type='income';
                            $mov->reason="Inventariar pedido";
                            $mov->amount=$delivery["products"][$index];
                            $mov->stock_before=$pto->inventory->stock_available;

                            // $mov->stock_after=$pto->inventory->stock_available-$element["amount"];
                            $mov->stock_after=$pto->inventory->stock_available+$delivery["products"][$index];

                            $mov->author=Auth::id();
                            $mov->request_id=$this->pedido->id;

                            //Guardo
                            if(!$mov->save()){
                                $this->dispatch('mostrarToast', 'Reportar inventario', 'Se ha generado un error al generar un movimiento, contacte a soporte');
                            }

                            //Ahora modifico el stock
                            $pto->inventory->stock_available=$mov->stock_after;

                            if(!$pto->inventory->save()){
                                $this->dispatch('mostrarToast', 'Reportar inventario', 'Se ha generado un error al actualizar stock, contacte a soporte');
                            }
                        }
                    }

                    //Ahora indico que ya inventarié
                    $todos=json_decode($this->pedido->delivery_list,true);
                    $todos[$fecha]["inventoried"]=True;
                    $todos[$fecha]["inventoried_date"]=Carbon::now()->toDateTimeString();
                    $todos[$fecha]["inventoried_by"]=Auth::id();

                    //Modifico y almaceno
                    $this->pedido->delivery_list=json_encode($todos);

                    //Guardo
                    if($this->pedido->save()){
                        $this->dispatch('mostrarToast', 'Reportar inventario', 'Se ha reportado en inventario la entrega del dia '.$fecha);
                        registrarLog("Inventario","Pedidos","Reportar inventario","Se ha reportado el inventario del pedido con id # : ".$this->pedido->id." y fecha: ".$fecha,true);
                    }else{
                        $this->dispatch('mostrarToast', 'Reportar inventario', 'Error reportando la entrega del dia '.$fecha);
                        registrarLog("Inventario","Pedidos","Reportar inventario","Se ha intentado reportar el inventario del pedido con id # : ".$this->pedido->id." y fecha: ".$fecha,false);
                    }

                }

            }
            $this->loadData();
            $this->pendienteInventariar=False;
        }
    }

    public function render()
    {
        return view('livewire.inventario.request-view');
    }
}
