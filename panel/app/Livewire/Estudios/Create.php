<?php

namespace App\Livewire\Estudios;

use Livewire\Component;

class Create extends Component
{

    public $ciudades;

    public function mount($Ciudades)
    {
        $this->ciudades = $Ciudades;
    }

    public function render()
    {
        return view('livewire.estudios.create');
    }
}
