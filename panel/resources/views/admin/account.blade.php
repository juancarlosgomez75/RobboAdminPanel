@extends('paneltemplate')
@section('title','Visualización de cuenta')

@section("contenido")
@livewire("admin.account",compact("usuario"))

@endsection
