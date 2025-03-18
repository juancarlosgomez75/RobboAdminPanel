<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(){
        return view("inventario.index");
    }

    public function viewedit($idproducto){
        //Consulto si el producto existe
        $producto=Product::find($idproducto);
        if($producto){
            return view("inventario.viewedit",compact("producto"));
        }else{
            return redirect(route("inventario.index"));
        }
        
    }
}
