<?php

namespace App\Livewire\Estudios;

use Livewire\Component;

class Viewedit extends Component
{
    public $editing=false;

    public $estudioactual;
    public $managers;
    public $ciudades;

    public $ciudadactual;

    public function mount($EstudioActual,$Managers,$Ciudades,$CiudadActual){
        $this->estudioactual=$EstudioActual;
        $this->managers = $Managers;
        $this->ciudades = $Ciudades;
        $this->ciudadactual = $CiudadActual;
    }

    public function activarEdicion(){
        $this->editing=true;

    }
    public function render()
    {
        return view('livewire.estudios.viewedit',["estudioactual"=>$this->estudioactual, "managers"=> $this->managers,"Ciudades"=> $this->ciudades,"CiudadActual"=>$this->ciudadactual]);
    }
}
