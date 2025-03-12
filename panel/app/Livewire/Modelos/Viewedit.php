<?php

namespace App\Livewire\Modelos;

use Livewire\Component;

class Viewedit extends Component
{
    public $editing=false;
    public function render()
    {
        return view('livewire.modelos.viewedit');
    }
}
