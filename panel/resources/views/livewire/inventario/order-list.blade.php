<div>
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <h5 class="card-title">Listado de órdenes</h5>
                    {{-- <p class="card-text">With supporting text below as a natural lead-in to additional content.</p> --}}
                </div>
                <div class="col-md-3 justify-content-end" style="display: flex; gap:0.6rem">
                    <a href="{{route("ordenes.create")}}" class="btn btn-sm action-btn btn-outline-secondary">
                        <i class="fa-solid fa-plus"></i> Crear
                    </a>
                    
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
                            <input type="text" class="custom-input" placeholder="Filtrar por destinatario" wire:model.change="filtroNombre">
                        </div>
                        <div class="col-md-3">
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
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 7%;">#</th>
                                <th scope="col">Fecha creación</th>
                                <th scope="col">Ciudad</th>
                                <th scope="col">Destinatario</th>
                                <th scope="col">Estado</th>
                                <th scope="col" style="width: 15%;"></th>
                            </tr>
                        </thead>
                        <tbody class="align-middle">
                            @if(!$pedidos->isEmpty())
                            @foreach($pedidos as $pedido)
                                <tr>
                                    <th scope="row">{{ $pedido->id }}</th>
                                    <td>{{ $pedido->created_at }}</td>
                                    <td>{{ $pedido->city }}</td>
                                    <td>{{ $pedido->name }}</td>
                                    <td>
                                        @if($pedido->status=="created")
                                        <span style="color:#e47d08">Creado</span>
                                        @elseif($pedido->status=="prepared")
                                        <span style="color:#004a8f">Preparado</span>
                                        @elseif($pedido->status=="waiting")
                                        <span style="color:#004a8f">Esperando recogida</span>
                                        @elseif($pedido->status=="sended")
                                        <span style="color:#0ea800">Enviado</span>
                                        @elseif($pedido->status=="canceled")
                                        <span style="color:#8f0000">Cancelado</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a type="button" class="btn btn-outline-secondary btn-sm" href="{{route("orden.ver",$pedido->id)}}">Ver detalles</a>
                                    </td>
                                </tr>
                            @endforeach
                            @else
                            <tr>
                                <td class="text-center" colspan="6">No se han encontrado órdenes</td>
                            </tr>
                            @endif
                            
                        </tbody>
                    </table>
                    
                </div>
                <div class="col-md-12">
                    {{ $pedidos->links() }}
                </div>
            </div>
        </div>
    </div>
    <br>
</div>