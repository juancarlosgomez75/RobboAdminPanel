<?php

namespace App\Livewire\Estudios;

use Livewire\Component;

class Viewedit extends Component
{
    public $editing=false;

    public function activarEdicion(){
        $this->editing=true;
        
    }
    public function render()
    {
        return view('livewire.estudios.viewedit');
    }
}
