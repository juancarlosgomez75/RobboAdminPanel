<?php

namespace App\Livewire\Admin;

use App\Models\Log;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Logs extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    //Valores de filtros
    public $filtrosActivos=false;
    public $filtroFecha="";
    public $filtroAccion="";
    public $filtroAutor="";

    public function updatingFiltroFecha()
    {
        $this->resetPage();
    }

    public function updatingFiltroAccion()
    {
        $this->resetPage();
    }

    public function updatingFiltroAutor()
    {
        $this->resetPage();
    }

    public function switchFiltros()
    {
        $this->filtrosActivos = !$this->filtrosActivos;
        
        if (!$this->filtrosActivos) {
            $this->filtroFecha= "";
            $this->filtroAccion = "";
            $this->filtroAutor = "";
        }
    }

    public function render()
    {
        
        $logs = Log::orderBy("created_at", "desc")
            ->when(!empty($this->filtroFecha), function ($query) {
                return $query->whereRaw("LOWER(created_at) LIKE ?", [strtolower($this->filtroFecha) . '%']);
            })
            ->when($this->filtroAccion, function ($query) {
                return $query->whereRaw("LOWER(action) LIKE ?", ['%' . strtolower($this->filtroAccion) . '%']);
            })
            ->when($this->filtroAutor, function ($query) {
                return $query->whereRaw("LOWER(author) LIKE ?", ['%' . strtolower($this->filtroAutor) . '%']);
            })
            ->paginate(2);


        return view('livewire.admin.logs',compact('logs'));
    }
}
