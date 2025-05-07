@extends('paneltemplate')
@section('title','Dashboard')

@section("contenido")

<div class="card shadow-custom">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12 pt-1">
                @livewire("admin.dashboard")
            </div>
        </div>
    </div>
</div>

@endsection
