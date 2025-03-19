<?php

namespace App\Livewire\Inventario;

use Livewire\Component;

class OrderView extends Component
{
    public $orden;

    public function mount($orden){
        $this->orden = $orden;
    }
    
    public function render()
    {
        return view('livewire.inventario.order-view');
    }
}
