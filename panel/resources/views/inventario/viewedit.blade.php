@extends('paneltemplate')
@section('title','Detalles de producto')
@section("contenido")

@livewire('alerts')
@livewire("inventario.viewedit",compact("producto"))

@endsection
