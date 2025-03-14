<?php

namespace App\Livewire\Admin;

use App\Models\Rank;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Accounts extends Component
{
    public $username="";
    public $name="";
    public $email="";
    public $rank="0";
    public function render()
    {

        $accounts = User::all();

        $rangos=Rank::where("id","<",Auth::user()->rank)->get();
        

        return view('livewire.admin.accounts',compact('accounts','rangos'));
    }
}
