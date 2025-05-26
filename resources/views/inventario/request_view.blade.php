@extends('paneltemplate')
@section('title','Ver pedido')
@section("contenido")

@livewire("inventario.request-view",compact("pedido"))

@endsection
