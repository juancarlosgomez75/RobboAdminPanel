<?php

namespace App\Livewire\Estudios;

use Livewire\Component;
use Illuminate\Support\Collection;

class Listado extends Component
{
    public $filtroNombre = "";
    public $filtroCiudad = "";
    public $filtrosActivos = false;
    public $datos;

    public function switchFiltros()
    {
        $this->filtrosActivos = !$this->filtrosActivos;
        
        if (!$this->filtrosActivos) {
            $this->filtroNombre = "";
            $this->filtroCiudad = "";
        }
    }
    

    public function mount($datos)
    {
        $this->datos = $datos;
    }


    public function render()
    {
        // Filtrar los datos
        $filtrados = array_filter($this->datos, function ($dato) {
            $nombreCoincide = empty($this->filtroNombre) || stripos($dato["StudyName"], $this->filtroNombre) !== false;
            $ciudadCoincide = empty($this->filtroCiudad) || stripos($dato["City"], $this->filtroCiudad) !== false;
            return $nombreCoincide && $ciudadCoincide;
        });

        //Ordeno
        usort($filtrados, function ($a, $b) {
            return strcmp($a["StudyName"], $b["StudyName"]);
        });

        return view('livewire.estudios.listado', [
            'texto' => $this->filtroNombre,
            'filtroOn' => $this->filtrosActivos,
            'datosUsar' => $filtrados
        ]);

    }
}
