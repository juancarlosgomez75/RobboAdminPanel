<div>
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-9">
                    <h5 class="card-title">Listado de máquinas</h5>
                    {{-- <p class="card-text">With supporting text below as a natural lead-in to additional content.</p> --}}
                </div>
                <div class="col-md-3 justify-content-end" style="display: flex; gap:0.6rem">
                    <a href="maquinas/crear" class="btn btn-sm action-btn btn-outline-secondary">
                        <i class="fa-solid fa-plus"></i> Crear
                    </a>
                    
                    <a type="button" class="btn btn-sm action-btn btn-outline-secondary" wire:click="switchFiltros()">
                        <i class="fa-solid fa-filter"></i> Filtros
                    </a>
                    
                </div>
                <div class="col-md-12 @if(!$filtroOn) hide @endif" id="Filtros">

                    <div class="row">
                        <div class="col-md-3">
                            <input type="text" class="custom-input" placeholder="Filtrar por número" wire:model.change="filtroHardware">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="custom-input" placeholder="Filtrar por ciudad" wire:model.change="filtroCiudad">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="custom-input" placeholder="Filtrar por estudio" wire:model.change="filtroEstudio">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th scope="col" style="width: 7%;">#</th>
                                <th scope="col">Hardware</th>
                                <th scope="col">Tipo</th>
                                <th scope="col">Ciudad</th>
                                <th scope="col">Estudio</th>
                                <th scope="col" style="width: 15%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($Machines))
                            @foreach($Machines as $index => $Maquina)
                                <tr>
                                    <th scope="row">{{ $Maquina['ID'] }}</th>
                                    <td>{{ $Maquina["FirmwareID"] ?? 'N/R' }}</td>
                                    <td>{{ $Maquina['Tipo'] ?? 'N/R' }}</td>
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