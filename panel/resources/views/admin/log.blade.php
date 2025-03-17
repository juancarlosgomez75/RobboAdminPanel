@extends('paneltemplate')
@section('title','Visualización de log')

@section("contenido")

<div class="card shadow-custom">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <h5 class="card-title">Información de log</h5>
                <p class="card-text">Esta es la información almacenada actualmente para este log</p>
            </div>
            <div class="col-md-12 pt-3">
                <table class="table">
                    <tr>
                        <th scope="row">Menú</th>
                        <td>{{$log->menu}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Sección</th>
                        <td>{{$log->section}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Acción</th>
                        <td>{{$log->action}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Detalles</th>
                        <td>{{$log->details}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Autor</th>
                        <td>{{$log->author_info->name}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Ip</th>
                        <td>{{$log->ip_address}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Resultado</th>
                        <td>
                            @if($log->result)
                            <span style="color: green;">Exitoso</span>
                            @else
                            <span style="color: red;">Fallido</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Fecha</th>
                        <td>{{$log->created_at}}</td>
                    </tr>
                  </table>
            </div>
        </div>
    </div>
</div>

@endsection
