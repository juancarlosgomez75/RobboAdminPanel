<div>
    {{-- {{json_encode($informacion)}} --}}
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <h4 class="card-title">Generación de reportes</h4>
                    <p class="card-text">Desde aquí podrás generar los reportes que desees. Para iniciar, por favor ingresa un rango de fechas y selecciona el estudio.</p>
                </div>

                @if(!$ejecutandoReporte)
                <div class="col-md-12 mb-3">
                    <p class="card-text">Por favor completa la información y luego presiona en <b>Generar Reporte</b>. Por seguridad sólo se podrá hacer uso de un intervalo de 15 días.</p>
                </div>
                <div class="col-md-3">
                    <label>Inicio:</label>
                    <input type="date" id="fecha" class="form-control" wire:model="fechaInicio">
                </div>
                <div class="col-md-3">
                    <label>Cierre:</label>
                    <input type="date" id="fecha" class="form-control" wire:model="fechaFin">
                </div>
                <div class="col-md-4">
                    <label>Seleccione un estudo:</label>
                    <select class="form-select" aria-label="Default select example" wire:model="estudioSeleccionado">
                        <option disabled value="0" selected>Selecciona una opción</option>
                        @if (!empty(($informacion)))
                        @foreach($informacion as $estudio)
                        <option value="{{$estudio["Id"]}}">{{$estudio["StudyName"]." (".$estudio["City"].")"}}</option>
                        @endforeach
                        
                        @endif

                    </select>
                </div>
                <div class="col-md-2">
                    <br>
                    <button type=("button" class="btn btn-primary" wire:click="generarReporte()">Generar reporte</button>
                </div>
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