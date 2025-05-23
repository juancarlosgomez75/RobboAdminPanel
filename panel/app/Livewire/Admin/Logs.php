<?php

namespace App\Livewire\Admin;

use App\Models\Log;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;
use Carbon\Carbon;

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

    public function deleteLogs(){
        //Borro los logs
        DB::table('logs')->truncate();

        registrarLog("Administracion","Logs","Borrar","Ha borrado todos los logs",true);

        $this->dispatch('mostrarToast', 'Borrar logs', "Se han borrado los logs registrados");


    }

    public function render()
    {
        
        $logs = Log::orderBy("created_at", "desc")
        ->when(!empty($this->filtroFecha), function ($query) {
            return $query->whereRaw("TO_CHAR(created_at, 'YYYY-MM-DD HH24:MI:SS') LIKE ?", [$this->filtroFecha . '%']);
        })
        ->when($this->filtroAccion, function ($query) {
            return $query->whereRaw("LOWER(action) LIKE ?", [strtolower($this->filtroAccion) . '%']);
        })
        ->when($this->filtroAutor, function ($query) {
            return $query->whereHas('author_info', function ($q) {
                $q->whereRaw("LOWER(name) LIKE ?", [strtolower($this->filtroAutor) . '%']);
            });
        })
        ->paginate(80);


        return view('livewire.admin.logs',compact('logs'));
    }
}
