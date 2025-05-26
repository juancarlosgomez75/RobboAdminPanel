<div>
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <h5 class="card-title">Listado de pedidos</h5>
                    {{-- <p class="card-text">With supporting text below as a natural lead-in to additional content.</p> --}}
                </div>
                <div class="col-md-3 justify-content-end" style="display: flex; gap:0.6rem">
                    @if(auth()->check() && auth()->user()->rank >= 4)
                    <a href="{{route("pedidos.create")}}" class="btn btn-sm action-btn btn-outline-secondary">
                        <i class="fa-solid fa-plus"></i> Crear
                    </a>
                    @endif
                    
                    <a type="button" class="btn btn-sm action-btn btn-outline-secondary" wire:click="switchFiltros()">
                        <i class="fa-solid fa-filter"></i> Filtros
                    </a>
                    
                </div>
                <div class="col-md-12 @if(!$filtrosActivos) hide @endif" id="Filtros">

                    <div class="row">
                        <div class="col-md-2">
                            <input type="text" class="custom-input" placeholder="Filtrar por fecha de creación" wire:model.change="filtroFecha">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="custom-input" placeholder="Filtrar por proveedor" wire:model.change="filtroEmpresa">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="custom-input" placeholder="Filtrar por fecha de entrega" wire:model.change="filtroEntrega">
                        </div>
                        <div class="col-md-2">
                            <select class="custom-input" wire:model.change="filtroEstado">
                                <option value="" selected>Todos</option>
                                <option value="canceled">Cancelado</option>
                                <option value="created">Creado</option>
                                <option value="partial delivery">Entrega parcial</option>
                                <option value="delivered">Entregado</option>
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
                                <th scope="col">Proveedor</th>
                                <th scope="col">Fecha tentativa de entrega</th>
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
                                    <td>{{ $pedido->enterprise }}</td> 
                                    <td>{{ \Carbon\Carbon::parse($pedido->tentative_delivery_date)->toDateString() }}</td>
                                    <td>
                                        @if($pedido->status=="created")
                                        <span style="color:#e47d08">Creado</span>
                                        @elseif($pedido->status=="partial delivery")
                                        <span style="color:#004a8f">Entrega parcial</span>
                                        @elseif($pedido->status=="delivered")
                                        <span style="color:#0ea800">Entregado</span>
                                        @elseif($pedido->status=="canceled")
                                        <span style="color:#8f0000">Cancelado</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a type="button" class="btn btn-outline-secondary btn-sm" href="{{route("pedido.ver",$pedido->id)}}">Ver detalles</a>
                                    </td>
                                </tr>
                            @endforeach
                            @else
                            <tr>
                                <td class="text-center" colspan="6">No se han encontrado pedidos</td>
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