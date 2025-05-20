<div>
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <h5 class="card-title">Listado de pedidos</h5>
                    {{-- <p class="card-text">With supporting text below as a natural lead-in to additional content.</p> --}}
                </div>
                                <div class="col-md-3 justify-content-end" style="display: flex; gap:0.6rem">
                    <a href="{{route("pedidos.create")}}" class="btn btn-sm action-btn btn-outline-secondary">
                        <i class="fa-solid fa-plus"></i> Crear
                    </a>
                    
                    <a type="button" class="btn btn-sm action-btn btn-outline-secondary" wire:click="switchFiltros()">
                        <i class="fa-solid fa-filter"></i> Filtros
                    </a>
                    
                </div>
                <div class="col-md-12 @if(!$filtrosActivos) hide @endif" id="Filtros">

                    <div class="row">
                        <div class="col-md-2">
                            <input type="text" class="custom-input" placeholder="Filtrar por fecha" wire:model.change="filtroFecha">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="custom-input" placeholder="Filtrar por destinatario" wire:model.change="filtroNombre">
                        </div>
                        <div class="col-md-2">
                            <select class="custom-input" wire:model.change="filtroTipo">
                                <option value="0" selected>Todas</option>
                                <option value="-1">Envío</option>
                                <option value="1">Recogida</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="custom-input" wire:model.change="filtroEstado">
                                <option value="" selected>Todas</option>
                                <option value="canceled">Cancelado</option>
                                <option value="created">Creado</option>
                                <option value="prepared">Preparado</option>
                                <option value="waiting">Esperando recogida</option>
                                <option value="sended">Enviado</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
</div>