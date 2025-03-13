<?php

namespace App\Livewire\Maquinas;

use Livewire\Component;

class Index extends Component
{
    public $filtroHardware = "";
    public $filtroCiudad= "";
    public $filtroEstudio = "";
    public $filtrosActivos = false;

    public $Maquinas;

    public function switchFiltros()
    {
        $this->filtrosActivos = !$this->filtrosActivos;
        
        if (!$this->filtrosActivos) {
            $this->filtroHardware = "";
            $this->filtroCiudad = "";
            $this->filtroEstudio = "";
        }
    }

    public function mount($Maquinas){
        $this->Maquinas = $Maquinas;

        usort($this->Maquinas, function ($a, $b) {
            return strcmp($a["FirmwareID"], $b["FirmwareID"]);
        });
    }

    public function render()
    {

        $filtrados = array_values(array_filter($this->Maquinas, function ($dato) {
            return 
                (empty($this->filtroCiudad) || 
                    (isset($dato["Location"]) && stripos(mb_strtolower($dato["Location"]), mb_strtolower($this->filtroCiudad)) !== false)) 
                &&
                (empty($this->filtroHardware) || 
                    (isset($dato["FirmwareID"]) && stripos(mb_strtolower($dato["FirmwareID"]), mb_strtolower($this->filtroHardware)) !== false))
                &&
                (empty($this->filtroEstudio) || 
                    (isset($dato["StudyData"]["StudyName"]) && stripos(mb_strtolower($dato["StudyData"]["StudyName"]), mb_strtolower($this->filtroEstudio)) !== false));
        }));
        
        
        
        return view('livewire.maquinas.index',['filtroOn' => $this->filtrosActivos,"Machines"=>$filtrados]);
    }
}
