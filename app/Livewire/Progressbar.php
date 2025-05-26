<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Cache;

class Progressbar extends Component
{
    public $userId;
    public $functionId;
    public $endSignal;
    public $progreso = 0;

    public function mount($userId,$functionId,$endSignal){
        $this->userId=$userId;
        $this->functionId=$functionId;
        $this->endSignal=$endSignal;
    }
    public function render()
    {
        $this->progreso = Cache::get($this->functionId."_" . $this->userId, 0);

        if($this->progreso==100){
            //Indico que ya terminé
            $this->dispatch($this->endSignal);

            //Reinicio la variable
            Cache::forget($this->functionId."_" . $this->userId);
        }

        return view('livewire.progressbar');
    }
}
