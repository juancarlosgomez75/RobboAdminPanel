<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On; 

class Alerts extends Component
{
    public $toasts = [];

    #[On('mostrarToast')] 
    public function crearToast($title,$message)
    {
        $this->toasts[] = ["Title"=>$title, "Message"=>$message];

        // Cierra el toast después de 10 segundos (10000 ms)
        $this->dispatch('closeToast', 5000);
    }

    public function removeToast($index)
    {
        unset($this->toasts[$index]);
        $this->toasts = array_values($this->toasts); // Reindexar el array
    }

    public function render()
    {
        
        return view('livewire.alerts');
    }
}
