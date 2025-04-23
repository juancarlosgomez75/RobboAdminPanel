<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class Progressbar extends Component
{
    public $userId;
    public $progreso = 0;
    public $resultados="";

    public function mount($userId){
        $this->userId=$userId;
    }
    public function render()
    {
        $this->progreso = Cache::get("reportProgress_" . $this->userId, 0);
        $this->resultados = Cache::get("reportResult_" . $this->userId, 0);

        if($this->progreso==100){
            $this->dispatch('progressDone');
        }

        return view('livewire.progressbar');
    }
}
