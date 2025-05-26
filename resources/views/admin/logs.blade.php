@extends('paneltemplate')
@section('title','Visualización de logs')

<style>
    .custom-input {
        display: flex;
        align-items: center;
        padding: 4px 10px; /* Reduce la altura */
        border: 1px solid #ddd; /* Borde gris claro */
        border-radius: 6px;
        width: 100%;
        font-size: 14px;
        margin-bottom:0.5rem;
        height: 30px; /* Controla la altura */
    }
    
    .custom-input:focus {
        outline: none;
        box-shadow: none; /* Elimina borde azul en focus */
        border-color: #ccc; /* Borde ligeramente más oscuro al enfocar */
    }
    
    .hide{
        display: none;
    }
    
    </style>

@section("contenido")
@livewire("admin.logs")

@endsection
