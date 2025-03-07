<?php

namespace App\Livewire\Estudios;

use Livewire\Component;

class Listado extends Component
{
    public $filtroNombre="";
    public $filtrociudad;

    public $filtrosActivos=false;

    public $datos;

    public function switchFiltros(){
        if($this->filtrosActivos){
            $this->filtrosActivos=false;
            $this->filtroNombre="";
        }else{
            $this->filtrosActivos=true;
        }
    }

    public function mount($datos){
        $this->datos = $datos;
    }

    public function render()
    {
        return view('livewire.estudios.listado',['texto'=>$this->filtroNombre,"filtroOn"=>$this->filtrosActivos]);
        
    }
}
