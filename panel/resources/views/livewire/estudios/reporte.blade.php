<div>
    {{-- {{json_encode($informacion)}} --}}
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <h4 class="card-title">Generación de reportes</h4>
                    <p class="card-text">Desde aquí podrás generar los reportes que desees. Para iniciar, por favor ingresa un rango de fechas y selecciona el estudio.</p>
                </div>

                @if(!$ejecutandoReporte & !$reporteListo)
                <div class="col-md-12 mb-3">
                    <p class="card-text">Por favor completa la información y luego presiona en <b>Generar Reporte</b>. Por seguridad sólo se podrá hacer uso de un intervalo de 15 días.</p>
                </div>
                <div class="col-md-3 mb-3">
                    <label>Fecha de nicio:</label>
                    <input type="date" id="fecha" class="form-control" wire:model="fechaInicio" max="{{ $fechaHoy }}">
                </div>
                <div class="col-md-3 mb-3">
                    <label>Fecha de cierre:</label>
                    <input type="date" id="fecha" class="form-control" wire:model="fechaFin" max="{{ $fechaHoy }}">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Seleccione un tipo:</label>
                    <select class="form-select" aria-label="Default select example" wire:model.change="tipoReporte">
                        <option disabled value="0">Selecciona una opción</option>
                        <option value="1">Ver todos los estudios</option>
                        <option value="2">Ver algún/algunos estudios</option>

                    </select>
                </div>

                {{-- <div class="col-md-2">
                    <br>
                    <button type=("button" class="btn btn-primary" wire:click="generarReporte()">Generar reporte</button>
                </div> --}}
                @if($tipoReporte !="2")
                <div class="col-md-2">
                    <br>
                    <button type="button" class="btn btn-outline-primary" wire:click="completarReporte()">Generar reporte</button>
                </div>
                @endif

                @if($tipoReporte =="2")
                <div class="col-md-6 mb-3">
                    <label>Seleccione un estudio para añadir:</label>
                    <select class="form-select" aria-label="Default select example" wire:model="estudioActual">
                        <option disabled value="0" selected>Selecciona una opción</option>
                        @if (!empty(($informacion)))
                        @foreach($informacion as $index=>$estudio)
                        @if(!in_array($index,$indexSeleccionados))
                        <option value="{{$index+1}}">{{$estudio["StudyName"]." (".$estudio["City"].")"}}</option>
                        @endif
                        @endforeach
                        
                        @endif

                    </select>
                </div>
                <div class="col-md-4">
                    <br>
                    <button type="button" class="btn btn-outline-secondary" wire:click="adicionarEstudio()">Añadir Estudio</button>
                    <button type="button" class="btn btn-outline-primary ms-3" wire:click="completarReporte()">Generar reporte</button>
                </div>
                <div class="col-md-12">
                    <p>Estudios añadidos:</p>
                    @foreach($indexSeleccionados as $indice=>$est)
                    <span class="badge bg-secondary text-white d-inline-flex align-items-center">
                        {{$informacion[$est]["StudyName"]." (".$informacion[$est]["City"].")"}}
                        <button type="button" class="btn-close btn-close-white btn-sm ms-2" aria-label="Cerrar" wire:click="quitarEstudio({{$indice}})"></button>
                    </span>
                    @endforeach

                </div>
                @endif

                @endif

                @if($ejecutandoReporte)
                <div class="col-md-12">
                    <label>Generando reporte...</label>
                    @livewire("progressbar", ["userId" => Auth::user()->id,"functionId"=>"reportProgress","endSignal"=>"progressDone"])
                </div>
                @endif
                
                @if($reporteListo)
                <div class="col-md-2">
                    reporte listillo
                </div>
                @endif

            </div>
        </div>
    </div>


    <br>


</div>