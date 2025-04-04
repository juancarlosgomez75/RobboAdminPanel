@extends('paneltemplate')
@section('title','Productos')
@section("contenido")
@livewire('alerts')
<div class="card shadow-custom">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <h5 class="card-title">Categorías</h5>
                <p class="card-text">Las categorías sirven para agrupar ciertos productos, esto con el fin de organizarlos y facilitar su búsqueda.</p>
            </div>
            <div class="col-md-12 pt-3">
                @livewire("inventario.categorias")
            </div>
            <div class="col-md-12">
                <h5 class="card-title">Productos</h5>
                <p class="card-text">Los productos son todos aquellos elementos que dispones en tu inventario y con los cuales comercias.</p>
            </div>
            <div class="col-md-12 pt-3">
                @livewire("inventario.productos")
            </div>
        </div>
    </div>
</div>

@endsection
