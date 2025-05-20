<?php

namespace App\Livewire\Inventario;

use Livewire\Component;

class RequestView extends Component
{
    public $pedido;
    public $pendientes=[];

    public function mount($pedido){
        //Cargo la info
        $this->pedido=$pedido;

        //Cargo los pendientes
        foreach(json_decode($pedido->creation_list,true) as $producto){
            $this->pendientes[]=$producto["amount"];
        }

        //Ahorra recorro los delivery
        if(!is_null($this->pedido->delivery_list)){
            foreach(json_decode($this->pedido->delivery_list,true) as $fecha=>$delivery){
                //Ahora recorro los productos
                foreach($delivery["products"] as $index=>$producto){
                    $this->pendientes[$index]=$this->pendientes[$index]-$producto["amount"];
                }
            }
        }

    }
    public function render()
    {
        return view('livewire.inventario.request-view');
    }
}
