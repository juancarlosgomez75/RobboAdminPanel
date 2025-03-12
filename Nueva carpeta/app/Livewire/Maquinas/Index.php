<?php

namespace App\Livewire\Maquinas;

use Livewire\Component;

class Index extends Component
{
    public $filtroHardware = "";
    public $filtroTipo = "";
    public $filtroEstudio = "";
    public $filtrosActivos = false;

    public $Maquinas;

    public function switchFiltros()
    {
        $this->filtrosActivos = !$this->filtrosActivos;
        
        if (!$this->filtrosActivos) {
            $this->filtroHardware = "";
            $this->filtroTipo = "";
            $this->filtroEstudio = "";
        }
    }

    public function mount($Maquinas){
        $this->Maquinas = $Maquinas;
    }

    public function render()
    {
        return view('livewire.maquinas.index',['filtroOn' => $this->filtrosActivos,"Maquinas"=>$this->Maquinas]);
    }
}
