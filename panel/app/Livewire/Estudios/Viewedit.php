<?php

namespace App\Livewire\Estudios;

use Livewire\Component;

class Viewedit extends Component
{
    public $editing=false;
    public $managers;

    public function mount($Managers){
        $this->managers = $Managers;
    }

    public function activarEdicion(){
        $this->editing=true;

    }
    public function render()
    {
        return view('livewire.estudios.viewedit',["managers"=> $this->managers]);
    }
}
