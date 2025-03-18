@extends('paneltemplate')
@section('title','Crear movimiento')
@section("contenido")

@livewire('alerts')
@livewire("inventario.movement",compact("idinventory"))

@endsection
