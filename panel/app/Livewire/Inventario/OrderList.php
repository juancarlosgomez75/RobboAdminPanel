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
    public $filtroTipo="0";
    public $filtrosActivos = false;

    public function switchFiltros()
    {
        $this->filtrosActivos = !$this->filtrosActivos;
        
        if (!$this->filtrosActivos) {
            $this->filtroNombre = "";
            $this->filtroFecha = "";
            $this->filtroEstado="";
            $this->filtroTipo="0";
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
        ->when($this->filtroTipo=="-1" || $this->filtroTipo=="1", function ($query) {
            return $query->where("type", ($this->filtroTipo=="-1")?'shipping':'collection');
        })
        ->paginate(20);


        return view('livewire.inventario.order-list',compact("pedidos"));
    }
}
