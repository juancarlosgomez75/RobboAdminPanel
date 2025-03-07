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
        $datosEnviar = empty($this->filtroNombre) ? $this->datos : array_filter($this->datos, function ($dato) {
            return stripos($dato["Nombre"], $this->filtroNombre) !== false;
        });

        return view('livewire.estudios.listado', [
            'texto' => $this->filtroNombre,
            'filtroOn' => $this->filtrosActivos,
            'datosUsar' => $datosEnviar, // Pasamos los datos filtrados
        ]);
    }
}


