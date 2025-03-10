<?php

namespace App\Livewire\Estudios;

use Livewire\Component;

class Viewedit extends Component
{
    public $editing=false;
    public $managers;
    public $ciudades;

    public $ciudadactual;

    public function mount($Managers,$Ciudades,$CiudadActual){
        $this->managers = $Managers;
        $this->ciudades = $Ciudades;
        $this->ciudadactual = $CiudadActual;
    }

    public function activarEdicion(){
        $this->editing=true;

    }
    public function render()
    {
        return view('livewire.estudios.viewedit',["managers"=> $this->managers,"Ciudades"=> $this->ciudades,"CiudadActual"=>$this->ciudadactual]);
    }
}
