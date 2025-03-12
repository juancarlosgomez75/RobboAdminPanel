<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ModelController extends Controller
{
    public function viewedit($idmodelo){
        return view("modelos.viewedit");
    }
}
