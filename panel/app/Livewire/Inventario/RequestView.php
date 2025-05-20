<?php

namespace App\Livewire\Inventario;

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
            $delivery=json_encode($this->pedido->delivery_list,True);
        }

        //Genero la estructura
        $delivery[Carbon::now()->toDateString()]=[
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

        }else{
            $this->dispatch('mostrarToast', 'Reportar entrega', "Error guardando la entrega, contacte a soporte");
        }
    }

    public function render()
    {
        return view('livewire.inventario.request-view');
    }
}
