@extends('paneltemplate')
@section('title','Ver orden')
@section("contenido")

@livewire("inventario.order-view",compact("orden"))

@endsection
