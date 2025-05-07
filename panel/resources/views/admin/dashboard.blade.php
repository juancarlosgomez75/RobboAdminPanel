@extends('paneltemplate')
@section('title','Dashboard')

@section("contenido")

<div class="card shadow-custom">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <h5 class="card-title">Dashboard</h5>
                <p class="card-text">Esta es la información de Robbo hoy</p>
            </div>
            <div class="col-md-12 pt-3">
                @livewire("admin.dashboard")
            </div>
        </div>
    </div>
</div>

@endsection
