<?php

namespace App\Livewire\Inventario;

use Livewire\Component;

class RequestList extends Component
{
    public $filtrosActivos = false;

    public function switchFiltros()
    {
        $this->filtrosActivos = !$this->filtrosActivos;
        
        // if (!$this->filtrosActivos) {
        //     $this->filtroNombre = "";
        //     $this->filtroFecha = "";
        //     $this->filtroEstado="";
        //     $this->filtroTipo="0";
        // }
    }
    public function render()
    {
        return view('livewire.inventario.request-list');
    }
}
