@extends('paneltemplate')
@section('title','Ver máquina')


@section("contenido")

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
<div>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item "><a href="{{route("estudio.ver",$Maquina["ID"])}}" class="text-secondary">Estudio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Máquina</li>
        </ol>
    </nav>
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <h5 class="card-title">Información básica de máquina</h5>
                    <p class="card-text">Esta es la información que está registrada en la base de datos</p>
                </div>
                <div class="col-md-12 mb-3">
                    <table class="table">
                        <tr>
                            <th>Id </th>
                            <td>{{$Maquina["ID"]}}</td>
                        </tr>
                        <tr>
                            <th>FirmwareID</th>
                            <td>{{$Maquina["FirmwareID"]}}</td>
                        </tr>
                        <tr>
                            <th>Tipo</th>
                            <td>{{$Maquina["Tipo"] ?? "N/R"}}</td>
                        </tr>
                        <tr>
                            <th>Ciudad</th>
                            <td>{{$Maquina["City"]}}</td>
                        </tr>
                        <tr>
                            <th>Estudio actual</th>
                            <td>
                                <a href="{{route("estudio.ver",$Maquina["StudyData"]["Id"])}}">{{$Maquina["StudyData"]["StudyName"]}}</a>
                            </td>
                        </tr>
                    </table>
                    
                </div>
                <div class="col-md-12 mb-3">
                    <h5 class="card-title">Movimientos de la máquina</h5>
                    <p class="card-text">Estos son los movimientos registrados para esta máquina</p>
                </div>

                <div class="col-md-12">
                    @livewire("maquinas.view-movements", compact("Maquina"))
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirmación de registro</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    Al presionar en confirmar registro, confirma que la información aquí contenida es correcta.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Regresar</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:click="guardar" >Confirmar registro</button>
            </div>
        </div>
        </div>
    </div>


</div>
@livewire('alerts')
@endsection
