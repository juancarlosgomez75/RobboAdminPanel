<?php

namespace App\Livewire\Estudios;

use Livewire\Component;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class Listado extends Component
{
    public $filtroNombre = "";
    public $filtroCiudad = "";
    public $filtrosActivos = false;
    public $datos;
    public $perPage = 2; // Número de elementos por página

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
            $nombreCoincide = empty($this->filtroNombre) || stripos($dato["Nombre"], $this->filtroNombre) !== false;
            $ciudadCoincide = empty($this->filtroCiudad) || stripos($dato["Ciudad"], $this->filtroCiudad) !== false;
            return $nombreCoincide && $ciudadCoincide;
        });

        // Convertir en colección para paginar
        $collection = new Collection(array_values($filtrados));
        $currentPage = request()->query('page', 1); // Página actual

        // Paginar los datos filtrados
        $paginator = new LengthAwarePaginator(
            $collection->forPage($currentPage, $this->perPage), // Datos actuales
            $collection->count(), // Total de datos
            $this->perPage, // Elementos por página
            $currentPage, // Página actual
            ['path' => request()->url(), 'query' => request()->query()] // Mantener query params
        );

        if (!$this->filtrosActivos) {
            return view('livewire.estudios.listado', [
                'texto' => $this->filtroNombre,
                'filtroOn' => $this->filtrosActivos,
                'datosUsar' => $paginator,
            ]);
        }else{
            return view('livewire.estudios.listado', [
                'texto' => $this->filtroNombre,
                'filtroOn' => $this->filtrosActivos,
                'datosUsar' => $filtrados
            ]);
        }

    }
}
