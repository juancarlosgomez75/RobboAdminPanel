<div>
    {{-- {{json_encode($informacion)}} --}}
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <button wire:click="verReporte('Juan')">Ver reporte</button>

                <script>
                    document.addEventListener('livewire:init', () => {
                        Livewire.on('abrir-reporte', (event) => {
                            // Aquí accedemos a los datos necesarios
                            const title = event[0]["title"];
                            const otherData = event[0]["otherData"];  // Si tienes otros datos
                
                            // Creamos el formulario dinámicamente
                            const form = document.createElement('form');
                            form.method = 'POST'; // Enviar datos por POST
                            form.action = '{{ route('reporte.pdf') }}';  // Ruta del reporte PDF
                
                            // Añadimos un campo CSRF para protección
                            const csrfToken = document.createElement('input');
                            csrfToken.type = 'hidden';
                            csrfToken.name = '_token';
                            csrfToken.value = '{{ csrf_token() }}';
                            form.appendChild(csrfToken);
                
                            // Añadimos los parámetros necesarios al formulario
                            const titleInput = document.createElement('input');
                            titleInput.type = 'hidden';
                            titleInput.name = 'title'; // Nombre del parámetro
                            titleInput.value = title;
                            form.appendChild(titleInput);
                
                            const otherInput = document.createElement('input');
                            otherInput.type = 'hidden';
                            otherInput.name = 'otherData'; // Otro parámetro
                            otherInput.value = otherData;
                            form.appendChild(otherInput);
                
                            // Abrimos una nueva ventana usando window.open
                            const newWindow = window.open('', '_blank');  // Abre una nueva ventana en blanco
                
                            // Aseguramos que el formulario se envíe a esta nueva ventana
                            form.target = newWindow.name;  // Usamos el nombre de la nueva ventana
                
                            // Enviamos el formulario
                            newWindow.document.body.appendChild(form);  // Añadimos el formulario a la nueva ventana
                            form.submit();  // Enviamos el formulario a la nueva ventana
                
                            // Removemos el formulario después de enviarlo
                            document.body.removeChild(form);
                        });
                    });
                </script>
                

                <div class="col-md-12 mb-3">
                    <h4 class="card-title">Generación de reportes</h4>
                    <p class="card-text">Desde aquí podrás generar los reportes que desees. Sólo se mostrarán los estudios que posean registros en el rango de tiempo ingresado.</p>
                </div>

                @if(!$ejecutandoReporte & !$reporteListo)
                <div class="col-md-12 mb-3">
                    <p class="card-text">Para iniciar, por favor ingresa un rango de fechas y selecciona el estudio. Por favor completa la información y luego presiona en <b>Generar Reporte</b>. Por seguridad sólo se podrá hacer uso de un intervalo de 15 días.</p>
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
                @if($tipoReporte =="1")
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
                <div class="col-md-12">
                    {{-- {{json_encode($resultado)}} --}}
                    <div class="accordion" id="accordionPanelsStayOpenExample">
                        @foreach ($resultado as $index => $item)
                            @if(array_key_exists("ResultsReport", $item))
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $index }}">
                                    <button class="accordion-button {{ $index !== 0 ? 'collapsed' : '' }}" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $index }}"
                                            aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                            aria-controls="collapse{{ $index }}">
                                        Resultados para: {{ $item["StudyName"]." (".$item["City"].")" }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $index }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $index }}" data-bs-parent="#accordionPanelsStayOpenExample">
                                    <div class="accordion-body">
                                        <div class="row">
                                            <p><b>Información de acciones por máquinas:</b></p>

                                            <table class="table align-middle text-center">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" colspan="2">Acción</th>
                                                        @foreach ($item["ResultsReport"]["Maquinas"] as $Maquina=>$info)
                                                        <th scope="col">#{{$Maquina}}</th>
                                                        @endforeach
                                                        <th scope="col">Total</th>

                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    {{-- MOV --}}
                                                    <tr>
                                                        <td rowspan="2">MOV</td>
                                                        <td>Cantidad</td>
                                                        @foreach($item["ResultsReport"]["Maquinas"] as $Maquina=>$info)
                                                        <td>{{ $info["Acciones"]["MOV"]["Cantidad"] ?? 0 }}</td>
                                                        @endforeach
                                                        <td>{{ $item["ResultsReport"]["Acciones"]["MOV"]["Cantidad"] ?? 0 }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Tiempo (min)</td>
                                                        @foreach($item["ResultsReport"]["Maquinas"] as $Maquina=>$info)
                                                        <td>{{ number_format(($info["Acciones"]["MOV"]["Tiempo"] ?? 0) / 60, 2) }}</td>
                                                        @endforeach
                                                        <td>{{ number_format(($item["ResultsReport"]["Acciones"]["MOV"]["Tiempo"] ?? 0) / 60, 2) }}</td>
                                                    </tr>

                                                    {{-- CONTROL --}}
                                                    <tr>
                                                        <td rowspan="2">CONTROL</td>
                                                        <td>Cantidad</td>
                                                        @foreach($item["ResultsReport"]["Maquinas"] as $Maquina=>$info)
                                                        <td>{{ $info["Acciones"]["CONTROL"]["Cantidad"] ?? 0 }}</td>
                                                        @endforeach
                                                        <td>{{ $item["ResultsReport"]["Acciones"]["CONTROL"]["Cantidad"] ?? 0 }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Tiempo (min)</td>
                                                        @foreach($item["ResultsReport"]["Maquinas"] as $Maquina=>$info)
                                                        <td>{{ number_format(($info["Acciones"]["CONTROL"]["Tiempo"] ?? 0) / 60, 2) }}</td>
                                                        @endforeach
                                                        <td>{{ number_format(($item["ResultsReport"]["Acciones"]["CONTROL"]["Tiempo"] ?? 0) / 60, 2) }}</td>
                                                    </tr>

                                                    {{-- CUM --}}
                                                    <tr>
                                                        <td>CUM</td>
                                                        <td>Cantidad</td>
                                                        @foreach($item["ResultsReport"]["Maquinas"] as $Maquina=>$info)
                                                        <td>{{ $info["Acciones"]["CUM"]["Cantidad"] ?? 0 }}</td>
                                                        @endforeach
                                                        <td>{{ $item["ResultsReport"]["Acciones"]["CUM"]["Cantidad"] ?? 0 }}</td>
                                                    </tr>

                                                    {{-- SCUM --}}
                                                    <tr>
                                                        <td>SCUM</td>
                                                        <td>Cantidad</td>
                                                        @foreach($item["ResultsReport"]["Maquinas"] as $Maquina=>$info)
                                                        <td>{{ $info["Acciones"]["SCUM"]["Cantidad"] ?? 0 }}</td>
                                                        @endforeach
                                                        <td>{{ $item["ResultsReport"]["Acciones"]["SCUM"]["Cantidad"] ?? 0 }}</td>
                                                    </tr>

                                                    {{-- XCUM --}}
                                                    <tr>
                                                        <td>XCUM</td>
                                                        <td>Cantidad</td>
                                                        @foreach($item["ResultsReport"]["Maquinas"] as $Maquina=>$info)
                                                        <td>{{ $info["Acciones"]["XCUM"]["Cantidad"] ?? 0 }}</td>
                                                        @endforeach
                                                        <td>{{ $item["ResultsReport"]["Acciones"]["XCUM"]["Cantidad"] ?? 0 }}</td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                            <br>
                                            <p><b>Información de tokens por páginas y modelos:</b></p>

                                            <table class="table align-middle text-center">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Modelo</th>
                                                        @foreach ($item["ResultsReport"]["Paginas"] as $pagina=>$info)
                                                        <th scope="col">{{ $pagina == "SIMULADOR" ? "MANUAL" : $pagina }}</th>
                                                        @endforeach
                                                        <th scope="col">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($item["ResultsReport"]["Modelos"] as $modelo=>$info)
                                                    <tr>
                                                        <td>{{$modelo}}</td>
                                                        @foreach ($item["ResultsReport"]["Paginas"] as $pagina=>$_)
                                                        <td>{{$info["Paginas"][$pagina]["Tokens"]??0}}</td>
                                                        @endforeach
                                                        <td>{{$info["Tokens"]??0}}</td>

                                                    </tr>
                                                    @endforeach
                                                    <tr>
                                                        <th scope="row">Total</th>
                                                        @foreach ($item["ResultsReport"]["Paginas"] as $pagina=>$info)
                                                        <td>{{$info["Tokens"]??0}}</td>
                                                        @endforeach
                                                        <th scope="row">{{$item["ResultsReport"]["Tokens"]}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <br>
                                            <p><b>Información de acciones por modelos:</b></p>

                                            <table class="table align-middle text-center">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Modelo</th>
                                                        <th scope="col">MOV (min)</th>
                                                        <th scope="col">CONTROL (min)</th>
                                                        <th scope="col">CUM</th>
                                                        <th scope="col">SCUM</th>
                                                        <th scope="col">XCUM</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($item["ResultsReport"]["Modelos"] as $modelo=>$info)
                                                    <tr>
                                                        <td>{{$modelo}}</td>
                                                        <td>{{ number_format(($info["Acciones"]["MOV"]["Tiempo"] ?? 0) / 60, 2) }}</td>
                                                        <td>{{ number_format(($info["Acciones"]["CONTROL"]["Tiempo"] ?? 0) / 60, 2) }}</td>
                                                        <td>{{ $info["Acciones"]["CUM"]["Cantidad"] ?? 0 }}</td>
                                                        <td>{{ $info["Acciones"]["SCUM"]["Cantidad"] ?? 0 }}</td>
                                                        <td>{{ $info["Acciones"]["XCUM"]["Cantidad"] ?? 0 }}</td>
                                                    </tr>
                                                    @endforeach
                                                    <tr>
                                                        <th scope="row">Total</th>
                                                        <td>{{ number_format(($item["ResultsReport"]["Acciones"]["MOV"]["Tiempo"] ?? 0) / 60, 2) }}</td>
                                                        <td>{{ number_format(($item["ResultsReport"]["Acciones"]["CONTROL"]["Tiempo"] ?? 0) / 60, 2) }}</td>
                                                        <td>{{ $item["ResultsReport"]["Acciones"]["CUM"]["Cantidad"] ?? 0 }}</td>
                                                        <td>{{ $item["ResultsReport"]["Acciones"]["SCUM"]["Cantidad"] ?? 0 }}</td>
                                                        <td>{{ $item["ResultsReport"]["Acciones"]["XCUM"]["Cantidad"] ?? 0 }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>



                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>


    <br>


</div>