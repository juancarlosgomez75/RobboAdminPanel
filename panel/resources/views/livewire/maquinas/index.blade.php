<div>
    {{-- {{json_encode($Machines)}} --}}
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <h5 class="card-title">Listado de máquinas</h5>
                    {{-- <p class="card-text">With supporting text below as a natural lead-in to additional content.</p> --}}
                </div>
                <div class="col-md-3 justify-content-end" style="display: flex; gap:0.6rem">
                    {{-- <a href="maquinas/crear" class="btn btn-sm action-btn btn-outline-secondary">
                        <i class="fa-solid fa-plus"></i> Crear
                    </a> --}}

                    <a type="button" class="btn btn-sm action-btn btn-outline-secondary" wire:click="switchFiltros()">
                        <i class="fa-solid fa-filter"></i> Filtros
                    </a>

                </div>
                <div class="col-md-12 @if(!$filtroOn) hide @endif" id="Filtros">

                    <div class="row">
                        <div class="col-md-2">
                            <input type="text" class="custom-input" placeholder="Filtrar por número" wire:model.change="filtroHardware">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="custom-input" placeholder="Filtrar por ciudad" wire:model.change="filtroCiudad">
                        </div>
                        <div class="col-md-2">
                            <input type="text" class="custom-input" placeholder="Filtrar por estudio" wire:model.change="filtroEstudio">
                        </div>
                        <div class="col-md-2">
                            <select class="form-select form-select-sm" wire:model.change="filtroEstadoEstudio">
                                <option value="-1">Todos los estudios</option>
                                <option value="0">Estudios inactivos</option>
                                <option value="1">Estudios activos</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table align-middle">
                        <thead style="font-size:14px">
                            <tr>
                                <th scope="col"># - Id</th>
                                <th scope="col" style="cursor: pointer;" wire:click="ordenarBy('hardware')">
                                    @if($ordenarPor=="hardware")
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
                                    Hardware
                                </th>
                                <th scope="col" style="cursor: pointer;" wire:click="ordenarBy('city')">
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
                                <th scope="col" style="cursor: pointer;" wire:click="ordenarBy('study')">
                                    @if($ordenarPor=="study")
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
                                    Estudio
                                </th>
                                <th scope="col" style="width: 15%;"></th>
                            </tr>
                        </thead>
                        <tbody style="font-size:12px">
                            @if(!empty($Machines))
                            @foreach($Machines as $index => $Maquina)
                                <tr>
                                    <th scope="row">{{ $index }} - {{ $Maquina['ID'] }}</th>
                                    <td>{{ $Maquina["FirmwareID"] ?? 'N/R' }}</td>
                                    <td>{{ $Maquina['Location'] ?? 'No especificada' }}</td>
                                    <td>{{ $Maquina['StudyData']["StudyName"] ?? 'No especificada' }}</td>
                                    <td>
                                        <a type="button" class="btn btn-outline-primary btn-sm" href="maquina/{{ $Maquina['ID'] }}">Visualizar</a>
                                    </td>
                                </tr>
                            @endforeach
                            @else
                            @endif

                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
    <br>
</div>
