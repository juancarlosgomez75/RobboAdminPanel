<?php

namespace App\Livewire\Estudios;

use Livewire\Component;

class Reporte extends Component
{
    public $informacion;
    public $managers;
    public $maquinas;
    public $ciudades;
    
    public function mount($Informacion,$Managers,$Maquinas,$Ciudades){
        $this->informacion=$Informacion;
        $this->managers = $Managers;
        $this->maquinas = $Maquinas;
        $this->ciudades = $Ciudades;
    }

    public function render()
    {
        return view('livewire.estudios.reporte');
    }
}
