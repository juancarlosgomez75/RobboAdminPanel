<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class Progressbar extends Component
{
    public $userId;
    public $functionId;
    public $progreso = 0;

    public function mount($userId,$functionId){
        $this->userId=$userId;
        $this->functionId=$functionId;
    }
    public function render()
    {
        $this->progreso = Cache::get($this->functionId."_" . $this->userId, 0);

        if($this->progreso==100){
            $this->dispatch('progressDone');
        }

        return view('livewire.progressbar');
    }
}
