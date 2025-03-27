<?php

namespace App\Livewire\Inventario;

use App\Models\Courier;
use Livewire\Component;

class Couriers extends Component
{
    public $name="";
    public $page="";
    public function guardar(){
        //Valido la información
        if(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->name) && !empty(trim($this->name)))){
            $this->dispatch('mostrarToast', 'Crear empresa', 'Error: El nombre de la empresa no es válido');
            return false;
        }
        elseif (!preg_match('/\bhttps?:\/\/[a-zA-Z0-9\-\.]+(\.[a-zA-Z]{2,})+(\/\S*)?\b/', $this->page) || empty(trim($this->page))) {
            $this->dispatch('mostrarToast', 'Crear empresa', 'Error: La página no es válida');
            return false;
        }

        //Creo la empresa
        $empresa=new Courier();
        $empresa->name=$this->name;
        $empresa->page=$this->page;

        //Intento guardar
        if($empresa->save()){
            $this->dispatch('mostrarToast', 'Crear empresa', 'Se ha creado la empresa de forma correcta');

            registrarLog("Inventario","Mensajeria","Crear","Se ha creado a la empresa con datos: ".json_encode($empresa),true);
        }else{
            $this->dispatch('mostrarToast', 'Crear empresa', 'Error al crear empresa, póngase en contacto con el administrador');
            registrarLog("Inventario","Mensajeria","Crear","Se ha intentado crear a la empresa con datos: ".json_encode($empresa),false);
        }
    }
    public function render()
    {
        $couriers=Courier::all();
        return view('livewire.inventario.couriers',compact('couriers'));
    }
}
