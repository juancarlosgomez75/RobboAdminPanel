<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class Dashboard extends Component
{
    public $WSConnection=False;
    
    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
