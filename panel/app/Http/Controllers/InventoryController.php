<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductInventory;
use App\Models\ProductOrder;
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

    public function movement($idinventario){
        //Consulto si el producto existe
        $inventario=ProductInventory::find($idinventario);
        if($inventario){
            return view("inventario.movement",["inventory"=>$inventario]);
        }else{
            return redirect(route("inventario.index"));
        }
        
    }

    public function order_create(){
        return view("inventario.order");
    }

    public function order_list(){
        return view("inventario.order_list");
    }

    public function order_view($idorden){
        //Localizar la orden
        $orden=ProductOrder::find($idorden);
        if($orden){
            return view("inventario.order_view",compact("orden"));
        }else{
            return redirect(route("ordenes"));
        }
        
    }
}
