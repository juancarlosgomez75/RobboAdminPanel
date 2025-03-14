<div>
    {{-- @if($alerta)
        @if($alerta_sucess!="")
        <div class="alert alert-success" role="alert">
            {{$alerta_sucess}}
        </div>
        @elseif($alerta_error!="")
        <div class="alert alert-danger" role="alert">
            {{$alerta_error}}
        </div>
        @elseif($alerta_warning!="")
        <div class="alert alert-warning" role="alert">
            {{$alerta_warning}}
        </div>
        @endif
    @endif --}}
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <h5 class="card-title">Historial de acciones realizadas</h5>
                    {{-- <p class="card-text">With supporting text below as a natural lead-in to additional content.</p> --}}
                </div>
                <div class="col-md-3 justify-content-end" style="display: flex; gap:0.6rem">
                    <a type="button" class="btn btn-sm action-btn btn-outline-secondary" wire:click="switchFiltros()">
                        <i class="fa-solid fa-filter"></i> Filtros
                    </a>
                </div>
                <div class="col-md-12 @if(!$filtrosActivos) hide @endif" id="Filtros">

                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" class="custom-input" placeholder="Filtrar por fecha" wire:model.change="filtroFecha">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="custom-input" placeholder="Filtrar por acción" wire:model.change="filtroAccion">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="custom-input" placeholder="Filtrar por autor" wire:model.change="filtroAutor">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Fecha y hora</th>
                                <th scope="col">Menu</th>
                                <th scope="col">Sección</th>
                                <th scope="col">Acción</th>
                                <th scope="col">Autor</th>
                                <th scope="col"></th>
                                <th scope="col" style="width: 15%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($logs))
                            @foreach ($logs as $log)
                                <tr>
                                    <td>{{$log->created_at}}</td>
                                    <td>{{$log->menu}}</td>
                                    <td>{{$log->section}}</td>
                                    <td>{{$log->action}}</td>
                                    <td>{{$log->author_info->username}}</td>
                                    <td>
                                        @if($log->result)
                                        <i class="fa-solid fa-circle-check" style="color: green;"></i>
                                        @else
                                        <i class="fa-solid fa-circle-xmark" style="color: red;"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <a type="button" class="btn btn-outline-secondary btn-sm">Ver detalles</a>
                                    </td>
                                </tr>
                            @endforeach
                            
                            @endif
                        </tbody>
                    </table>
                    
                </div>
                <div class="col-md-12">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>

    <br>
</div>