<?php

namespace App\Livewire\Maquinas;

use Livewire\Component;

class Index extends Component
{
    public $filtroHardware = "";
    public $filtroCiudad= "";
    public $filtroEstudio = "";
    public $filtrosActivos = false;
    public $filtroEstadoEstudio="-1";

    public $ordenarPor = "hardware";
    public $ordenarDesc = true;

    public $Maquinas;

    public function ordenarBy($filtro){
        //Analizo si cambia es la columna o la dirección
        if($filtro == "hardware" || $filtro == "city"  || $filtro == "study"){
            if($filtro != $this->ordenarPor){
                $this->ordenarPor = $filtro;
                $this->ordenarDesc = true;
            }else{
                $this->ordenarDesc = !$this->ordenarDesc;
            } 
        }
    }

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
                    (isset($dato["StudyData"]["StudyName"]) && stripos(mb_strtolower($dato["StudyData"]["StudyName"]), mb_strtolower($this->filtroEstudio)) !== false))
                &&
                (($this->filtroEstadoEstudio=="-1") || 
                    (isset($dato["StudyData"]["Active"]) && ( $dato["StudyData"]["Active"]==(($this->filtroEstadoEstudio=="1")?True:False))));
        }));

        //Ordeno según el tipo de ordenación
        if($this->ordenarPor=="hardware"){
            if($this->ordenarDesc){
                usort($filtrados, function ($a, $b) {
                    return strcmp($a["FirmwareID"], $b["FirmwareID"]);
                });
            }else{
                usort($filtrados, function ($a, $b) {
                    return strcmp($b["FirmwareID"], $a["FirmwareID"]);
                });
            }
        }else if($this->ordenarPor=="city"){
            if($this->ordenarDesc){
                usort($filtrados, function ($a, $b) {
                    return strcmp($a["Location"], $b["Location"]);
                });
            }else{
                usort($filtrados, function ($a, $b) {
                    return strcmp($b["Location"], $a["Location"]);
                });
            }
        }else if($this->ordenarPor=="study"){
            if($this->ordenarDesc){
                usort($filtrados, function ($a, $b) {
                    return strcmp($a['StudyData']["StudyName"], $b['StudyData']["StudyName"]);
                });
            }else{
                usort($filtrados, function ($a, $b) {
                    return strcmp($b['StudyData']["StudyName"], $a['StudyData']["StudyName"]);
                });
            }
        }
        
        
        
        return view('livewire.maquinas.index',['filtroOn' => $this->filtrosActivos,"Machines"=>$filtrados]);
    }
}
