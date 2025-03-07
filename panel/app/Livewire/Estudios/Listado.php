<?php

namespace App\Livewire\Estudios;

use Livewire\Component;

class Listado extends Component
{
    public $filtroNombre = "";
    public $filtrociudad = "";
    public $filtrosActivos = false;
    public $datos;

    public function switchFiltros()
    {
        $this->filtrosActivos = !$this->filtrosActivos;
        if (!$this->filtrosActivos) {
            $this->filtroNombre = "";
            $this->filtrociudad = "";
        }
    }

    public function mount($datos)
    {
        $this->datos = $datos;
    }

    public function render()
    {
        //Filtro los datos
        $datosFiltrados=$this->datos;

        if($this->filtroNombre != "") {
            $datosFiltrados=[];
        }

        return view('livewire.estudios.listado', [
            'texto' => $this->filtroNombre,
            'filtroOn' => $this->filtrosActivos,
            'datos' => $datosFiltrados, // Pasamos los datos filtrados
        ]);
    }
}


