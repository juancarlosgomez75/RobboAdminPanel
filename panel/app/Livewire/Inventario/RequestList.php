<?php

namespace App\Livewire\Inventario;

use App\Models\Request as ModelsRequest;
use Livewire\Component;
use Livewire\WithPagination;

class RequestList extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';    public $filtrosActivos = false;

    public $filtroFecha="";
    public $filtroEmpresa="";
    public $filtroEntrega="";
    public $filtroEstado="availables";

    public function switchFiltros()
    {
        $this->filtrosActivos = !$this->filtrosActivos;

        // if (!$this->filtrosActivos) {
        //     $this->filtroNombre = "";
        //     $this->filtroFecha = "";
        //     $this->filtroEstado="";
        //     $this->filtroTipo="0";
        // }
    }
    public function render()
    {
        $pedidos = ModelsRequest::orderBy("created_at", "desc")
        ->when(!empty($this->filtroFecha), function ($query) {
            return $query->whereRaw("TO_CHAR(created_at, 'YYYY-MM-DD HH24:MI:SS') LIKE ?", [$this->filtroFecha . '%']);
        })
        ->when(!empty($this->filtroEmpresa), function ($query) {
            return $query->whereRaw("enterprise LIKE ?", [strtolower($this->filtroEmpresa) . '%']);
        })
        ->when(!empty($this->filtroEntrega), function ($query) {
            return $query->whereRaw("TO_CHAR(tentative_delivery_date, 'YYYY-MM-DD HH24:MI:SS') LIKE ?", [$this->filtroEntrega . '%']);
        })
        ->when(true, function ($query) {
            switch ($this->filtroEstado) {
                case "all":
                    return $query;
                case "availables":
                    return $query->where("status", "!=", "canceled")->where("finished", false);
                case "cancelled":
                    return $query->where("status", "canceled");
                case "finished":
                    return $query->where("finished", true);
                default:
                    return $query->where("status", $this->filtroEstado)
                                ->where("status", "!=", "canceled")
                                ->where("finished", false);
            }
        })
        ->paginate(20);

        return view('livewire.inventario.request-list',compact("pedidos"));
    }
}
