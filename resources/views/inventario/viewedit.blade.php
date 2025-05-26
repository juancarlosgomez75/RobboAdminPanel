@extends('paneltemplate')
@section('title','Detalles de producto')
@section("contenido")


@livewire("inventario.viewedit",compact("producto"))

@endsection
