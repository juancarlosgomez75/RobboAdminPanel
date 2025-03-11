<?php

namespace App\Livewire\Maquinas;

use Livewire\Component;

class Index extends Component
{
    public $filtroNombre = "";
    public $filtroCiudad = "";
    public $filtrosActivos = false;

    public function switchFiltros()
    {
        $this->filtrosActivos = !$this->filtrosActivos;
        
        if (!$this->filtrosActivos) {
            $this->filtroNombre = "";
            $this->filtroCiudad = "";
        }
    }

    public function render()
    {
        return view('livewire.maquinas.index',['filtroOn' => $this->filtrosActivos]);
    }
}
