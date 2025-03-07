<div>
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <h5 class="card-title">Listado de estudios {{$texto}}</h5>
                    {{-- <p class="card-text">With supporting text below as a natural lead-in to additional content.</p> --}}
                </div>
                <div class="col-md-3 justify-content-end" style="display: flex; gap:0.6rem">
                    <button type="button" class="btn btn-sm action-btn btn-outline-secondary">
                        <i class="fa-solid fa-plus"></i> Crear
                    </button>
                    
                    <a type="button" class="btn btn-sm action-btn btn-outline-secondary" wire:click="switchFiltros()">
                        <i class="fa-solid fa-filter"></i> Filtros
                    </a>
                    
                </div>
                <div class="col-md-12 @if(!$filtroOn) hide @endif" id="Filtros">

                    <div class="row">
                        <div class="col-md-5">
                            <input type="text" class="custom-input" placeholder="Filtrar por nombre" wire:model.live="filtroNombre">
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="custom-input" placeholder="Filtrar por ciudad">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="w-10" scope="col" style="width: 7%;">#</th>
                                <th class="w-40" scope="col">Nombre</th>
                                <th class="w-30" scope="col">Ciudad</th>
                                <th class="w-15" scope="col">Modelos</th>
                                <th scope="col" style="width: 23%;">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($datosUsar as $index => $dato)
                                <tr>
                                    <th scope="row">{{ $index + 1 }}</th>
                                    <td>{{ $dato['Nombre'] ?? 'Sin Nombre' }}</td>
                                    <td>{{ $dato['Ciudad'] ?? 'No especificada' }}</td>
                                    <td>{{ $dato['Modelos'] ?? 'N/A' }}</td>
                                    <td>
                                        <a type="button" class="btn btn-outline-secondary btn-sm" href="modelos.php?id={{ $index }}">Ver</a>
                                        <a type="button" class="btn btn-outline-primary btn-sm" href="editarestudio.php?id={{ $index }}">Editar</a>
                                        <button type="button" class="btn btn-outline-danger btn-sm">Eliminar</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    
                </div>
            </div>

        </div>
    </div>
</div>