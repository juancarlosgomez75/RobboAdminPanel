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
    {{json_encode($Maquina)}}
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
                    <h5 class="card-title">Historial de autoclean</h5>
                    <p class="card-text">Aquí encontrarás los últimos 3 registros de Autoclean, almacenados en la base de datos.</p>
                </div>
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Fecha y hora</th>
                                <th scope="col">Paso 1</th>
                                <th scope="col">Paso 2</th>
                                <th scope="col">Paso 3</th>
                                <th scope="col">Paso 4</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($Maquina["AutoClean"]))
                            @foreach ($Maquina["AutoClean"] as $autoclean)
                            <tr>
                                <td>{{$autoclean["fecha"]}}</td>
                                <td>
                                    @if($autoclean["paso1Fin"] != "NO EJECUTADO")
                                        <span class="badge bg-success"
                                        data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-custom-class="custom-tooltip"
                                        data-bs-title="{{$autoclean["paso1Fin"]}}">Completado</span>
                                    @else
                                        <span class="badge bg-danger">No completado</span>
                                    @endif
                                </td>
                                <td>
                                    @if($autoclean["paso2Fin"] != "NO EJECUTADO")
                                        <span class="badge bg-success" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-custom-class="custom-tooltip"
                                        data-bs-title="{{$autoclean["paso2Fin"]}}">Completado</span>
                                    @else
                                        <span class="badge bg-danger">No completado</span>
                                    @endif
                                </td>
                                <td>
                                    @if($autoclean["paso3Fin"] != "NO EJECUTADO")
                                        <span class="badge bg-success" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-custom-class="custom-tooltip"
                                        data-bs-title="{{$autoclean["paso3Fin"]}}">Completado</span>
                                    @else
                                        <span class="badge bg-danger">No completado</span>
                                    @endif
                                </td>
                                <td>
                                    @if($autoclean["paso4Fin"] != "NO EJECUTADO")
                                        <span class="badge bg-success" data-bs-toggle="tooltip" data-bs-placement="top"
                                        data-bs-custom-class="custom-tooltip"
                                        data-bs-title="{{$autoclean["paso4Fin"]}}">Completado</span>
                                    @else
                                        <span class="badge bg-danger">No completado</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="5">No se han registrado autoclean</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    
                </div>
                <div class="col-md-12 mb-3">

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
<script>
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
</script>
@endsection
