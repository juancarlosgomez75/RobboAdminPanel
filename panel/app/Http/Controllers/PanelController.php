<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PanelController extends Controller
{
    public function profile_view(){
        return view("perfiles.perfil");
    }

    public function index(){
        return view("admin.index");
    }
}
