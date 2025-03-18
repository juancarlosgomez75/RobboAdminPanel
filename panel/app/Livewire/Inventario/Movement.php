<?php

namespace App\Livewire\Inventario;

use Livewire\Component;

class Movement extends Component
{
    public $idinventario;

    public function mount($idinventory){
        $this->idinventario = $idinventory;
    }
    public function render()
    {
        return view('livewire.inventario.movement');
    }
}
