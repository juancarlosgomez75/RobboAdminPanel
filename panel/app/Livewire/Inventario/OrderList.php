<?php

namespace App\Livewire\Inventario;

use App\Models\ProductOrder;
use Livewire\Component;
use Livewire\WithPagination;

class OrderList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $filtroNombre = "";
    public $filtroFecha = "";
    public $filtroEstado="";
    public $filtrosActivos = false;

    public function switchFiltros()
    {
        $this->filtrosActivos = !$this->filtrosActivos;
        
        if (!$this->filtrosActivos) {
        }
    }
    public function render()
    {
        $pedidos = ProductOrder::orderBy("created_at", "desc")
        ->when(!empty($this->filtroFecha), function ($query) {
            return $query->whereRaw("TO_CHAR(created_at, 'YYYY-MM-DD HH24:MI:SS') LIKE ?", [$this->filtroFecha . '%']);
        })
        ->when(!empty($this->filtroCiudad), function ($query) {
            return $query->whereRaw("city LIKE ?", [strtolower($this->filtroCiudad) . '%']);
        })
        ->when(!empty($this->filtroEstado), function ($query) {
            return $query->where("status", $this->filtroEstado);
        })
        ->paginate(20);


        return view('livewire.inventario.order-list',compact("pedidos"));
    }
}
