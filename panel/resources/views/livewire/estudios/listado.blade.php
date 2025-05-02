<div>
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <h5 class="card-title">Listado de estudios</h5>
                    {{-- <p class="card-text">With supporting text below as a natural lead-in to additional content.</p> --}}
                </div>
                <div class="col-md-3 justify-content-end" style="display: flex; gap:0.6rem">
                    <a href="estudios/crear" class="btn btn-sm action-btn btn-outline-secondary">
                        <i class="fa-solid fa-plus"></i> Crear
                    </a>
                    
                    <a type="button" class="btn btn-sm action-btn btn-outline-secondary" wire:click="switchFiltros()">
                        <i class="fa-solid fa-filter"></i> Filtros
                    </a>
                    
                </div>
                <div class="col-md-12 @if(!$filtroOn) hide @endif" id="Filtros">

                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" class="custom-input" placeholder="Filtrar por nombre" wire:model.change="filtroNombre">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="custom-input" placeholder="Filtrar por ciudad" wire:model.change="filtroCiudad">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select form-select-sm" wire:model.change="filtroEstado">
                                <option value="-1">Todos</option>
                                <option value="0">Inactivos</option>
                                <option value="1">Activos</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="w-10" scope="col" style="width: 7%;">#</th>
                                <th class="w-40" scope="col" style="cursor: pointer;" wire:click="ordenarBy('name')" >
                                    @if($ordenarPor=="name")
                                        @if($ordenarDesc)
                                            <a  class="text-decoration-none text-dark"> 
                                                <i class="fa-solid fa-angle-down me-2"></i>
                                            </a>
                                        @else
                                            <a class="text-decoration-none text-dark"> 
                                                <i class="fa-solid fa-angle-up me-2"></i>
                                            </a>
                                        @endif
                                    @endif
                                    Nombre 
                                </th>
                                <th class="w-30" scope="col" style="cursor: pointer;" wire:click="ordenarBy('city')">
                                    @if($ordenarPor=="city")
                                        @if($ordenarDesc)
                                            <a  class="text-decoration-none text-dark"> 
                                                <i class="fa-solid fa-angle-down me-2"></i>
                                            </a>
                                        @else
                                            <a class="text-decoration-none text-dark"> 
                                                <i class="fa-solid fa-angle-up me-2"></i>
                                            </a>
                                        @endif
                                    @endif
                                    Ciudad
                                </th>
                                <th></th>
                                <th scope="col" style="width: 15%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($datosUsar))
                            @foreach($datosUsar as $index => $dato)
                                <tr>
                                    <th scope="row">{{ $dato['Id'] }}</th>
                                    <td>{{ $dato["StudyName"] ?? 'Sin Nombre' }}</td>
                                    <td>{{ $dato['City'] ?? 'No especificada' }}</td>
                                    <td>
                                        @if($dato["Active"] )
                                        <i class="fa-solid fa-circle-check" style="color: green;"></i>
                                        @else
                                        <i class="fa-solid fa-circle-xmark" style="color: red;"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <a type="button" class="btn btn-outline-primary btn-sm" href="estudio/{{ $dato['Id'] }}">Visualizar</a>
                                    </td>
                                </tr>
                            @endforeach
                            @else
                            <tr class="text-center">
                                <td colspan="5">
                                    No se encontraron estudios
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                    
                </div>
            </div>
        </div>
    </div>
    <br>
</div>