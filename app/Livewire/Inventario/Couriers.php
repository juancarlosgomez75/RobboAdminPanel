<?php

namespace App\Livewire\Inventario;

use App\Models\Courier;
use Livewire\Component;

class Couriers extends Component
{
    public $name="";
    public $page="";


    public $courier_edit;
    public $name_edit="";
    public $page_edit="";

    public function editar($id){
        //Localizo
        $courier = Courier::find($id);

        if($courier){
            $this->courier_edit = $courier;
            $this->page_edit=$courier->page;
            $this->name_edit=$courier->name;
            $this->dispatch('abrirModalEdit');
        }else{
            $this->dispatch('mostrarToast', 'Editar empresa', 'Error: La empresa no fue encontrada');
        }
    }

    public function guardarCambios(){
        //Valido la información
        if(!(preg_match('/^[a-zA-Z0-9\/\-\áéíóúÁÉÍÓÚüÜñÑ\s]+$/', $this->name_edit) && !empty(trim($this->name_edit)))){
            $this->dispatch('mostrarToast', 'Editar empresa', 'Error: El nombre de la empresa no es válido');
            return false;
        }
        elseif (!preg_match('/\bhttps?:\/\/[a-zA-Z0-9\-\.]+(\.[a-zA-Z]{2,})+(\/\S*)?\b/', $this->page_edit) || empty(trim($this->page_edit))) {
            $this->dispatch('mostrarToast', 'Editar empresa', 'Error: La página no es válida');
            return false;
        }

        //Edito la empresa
        $this->courier_edit->name=$this->name_edit;
        $this->courier_edit->page=$this->page_edit;

        //Intengo guardar
        if($this->courier_edit->save()){

            //Logeo
            registrarLog("Inventario","Mensajeria","Editar","Se ha editado a la empresa con datos: ".json_encode($this->courier_edit),true);
            $this->dispatch('mostrarToast', 'Editar empresa', 'Se ha editado la empresa de forma correcta');
            $this->name_edit="";
            $this->page_edit="";

        }else{
            $this->dispatch('mostrarToast', 'Editar empresa', 'Error al editar empresa, póngase en contacto con el administrador');
            registrarLog("Inventario","Mensajeria","Editar","Se ha intentado editar a la empresa con datos: ".json_encode($this->courier_edit),false);
        }
    }

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
            $this->name="";
            $this->page="";
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
