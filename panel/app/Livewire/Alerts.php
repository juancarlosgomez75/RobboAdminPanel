<?php

namespace App\Livewire;

use Livewire\Component;

class Alerts extends Component
{
    public $toasts = [];
    protected $listeners = ['mostrarToast'];

    public function mostrarToast($titulo,$mensaje)
    {
        // Agregar nuevo toast al array
        $this->toasts[] = ['titulo'=>$titulo,'mensaje' => $mensaje];

        // Eliminar el mensaje después de 3 segundos
        $this->dispatch('ocultarToast');
    }

    public function eliminarToast()
    {
        array_shift($this->toasts); // Quita el primer mensaje
    }

    public function render()
    {
        
        return view('livewire.alerts');
    }
}
