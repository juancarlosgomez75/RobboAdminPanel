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
                            <input type="number" class="custom-input" placeholder="Filtrar por número" min="0">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="custom-input" placeholder="Filtrar por tipo">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="custom-input" placeholder="Filtrar por estudio">
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="w-10" scope="col" style="width: 7%;">#</th>
                                <th class="w-40" scope="col">Hardware</th>
                                <th class="w-40" scope="col">Tipo</th>
                                <th class="w-30" scope="col">Estudio</th>
                                <th scope="col" style="width: 15%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($Maquinas))
                            @foreach($Maquinas as $index => $Maquina)
                                <tr>
                                    <th scope="row">{{ $Maquina['Id'] }}</th>
                                    <td>{{ $Maquina["Hardware"] ?? 'N/R' }}</td>
                                    <td>{{ $Maquina['Tipo'] ?? 'No especificada' }}</td>
                                    <td>{{ $Maquina['Estudio'] ?? 'No especificada' }}</td>
                                    <td>
                                        <a type="button" class="btn btn-outline-primary btn-sm" href="estudio/">Visualizar</a>
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