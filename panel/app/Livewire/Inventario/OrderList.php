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
    public $filtroCiudad = "";
    public $filtrosActivos = false;

    public function switchFiltros()
    {
        $this->filtrosActivos = !$this->filtrosActivos;
        
        if (!$this->filtrosActivos) {
        }
    }
    public function render()
    {
        $pedidos=ProductOrder::orderBy("created_at", "desc")->paginate(10);
        return view('livewire.inventario.order-list',compact("pedidos"));
    }
}
