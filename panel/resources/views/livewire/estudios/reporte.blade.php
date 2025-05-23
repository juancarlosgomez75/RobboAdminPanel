<div>
    {{-- {{json_encode($informacion)}}
    {{-- {{json_encode($resultado)}} --}}
    {{-- {{json_encode($resultado)}}
    <br><br>
    {{json_encode($tiemposConexion)}}  --}}
    <div class="card shadow-custom">
        <div class="card-body">
            
            <div class="row">
            
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
                            @if($estudio["Active"])
                            <option value="{{$index+1}}">{{$estudio["StudyName"]." (".$estudio["City"].")"}}</option>
                            @endif
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
                @if($enviandoReportes)
                <div class="col-md-12">
                    <label>Enviando reportes...</label>
                    @livewire("progressbar", ["userId" => Auth::user()->id,"functionId"=>"reportSendProgress","endSignal"=>"progressSendDone"])
                </div>
                <br><br>
                @endif
                <div class="col-md-12">
                    {{-- {{json_encode($resultado)}} --}}
                    <div class="accordion" id="accordionPanelsStayOpenExample">
                        @if(!empty($resultado))
                        @foreach ($resultado as $index => $item)
                            @if(array_key_exists("ResultsReport", $item))
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading{{ $index }}">
                                    <button class="accordion-button collapsed" type="button"
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

                                            <style>
                                                .table-container {
                                                  max-width: width: calc(100% - 500px);; /* Asegura que el contenedor no exceda el ancho de la pantalla */
                                                  overflow-x: auto; /* Habilita el scroll horizontal */
                                                  -webkit-overflow-scrolling: touch; /* Mejora la experiencia en dispositivos móviles */
                                                }
                                              </style>

                                            <p><b>Información de acciones por máquinas:</b></p>
                                            <div class="table-container">
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
                                            </div>
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

                                            <br>
                                            <p><b>Información de tiempo (horas) de conexión de modelos por máquina:</b></p>
                                            <div class="table-container">
                                                <table class="table align-middle text-center">
                                                    @if(!empty($item["ConReport"]["Maquinas"]))
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Modelo</th>
                                                            @foreach($item["ConReport"]["Maquinas"] as $Maquina => $Tiempo)
                                                                <th scope="col">#{{ $Maquina }}</th>
                                                            @endforeach
                                                            <th scope="col">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($item["ConReport"]["Modelos"] as $Modelo => $DataModelo)
                                                            <tr>
                                                                <td>{{ $Modelo }}</td>
                                                                
                                                                @foreach($item["ConReport"]["Maquinas"] as $Maquina => $Tiempo)
                                                                    <td>{{ number_format(($DataModelo["Maquinas"][$Maquina] ?? 0) / 3600, 2) }}</td>
                                                                @endforeach

                                                                <td>{{ number_format($DataModelo["Total"] / 3600, 2) }}</td>
                                                            </tr>
                                                        @endforeach
                                                        <tr>
                                                            <td><b>Total:</b></td>
                                                            @foreach($item["ConReport"]["Maquinas"] as $Maquina => $Tiempo)
                                                                <td>{{ number_format($Tiempo / 3600, 2) }}</td>
                                                            @endforeach
                                                            <td><b>{{ number_format($item["ConReport"]["Total"] / 3600, 2) }}</b></td>
                                                        </tr>
                                                    </tbody>
                                                    @else
                                                    <tr class="text-center">
                                                        <td colspan="100%">No se encontraron registros de tiempo de conexión en este periodo</td>
                                                    </tr>
                                                    @endif
                                                </table>
                                            </div>


                                            @if($item["Renta"]=="Compartida")
                                            <br>
                                            <p><b>Información de cobros por acciones:</b></p>

                                            <table class="table align-middle text-center">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Acción</th>
                                                        <th scope="col">Cantidad</th>
                                                        <th scope="col">Valor unidad</th>
                                                        <th scope="col">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($item["CobrosTotales"] as $action=>$info)
                                                    @if($action!="Total")
                                                    <tr>
                                                        <td>{{$action}}</td>
                                                        @if($action=="MOV" || $action=="CONTROL")
                                                        <td>${{ number_format(($item["ResultsReport"]["Acciones"][$action]["Tiempo"] ?? 0) / 60, 2) }}</td>
                                                        @else
                                                        <td>${{ number_format(($item["ResultsReport"]["Acciones"][$action]["Cantidad"] ?? 0), 2) }}</td>
                                                        @endif
                                                        <td>${{ number_format(($item["Montos"][$action] ?? 0), 2) }}</td>
                                                        <td>${{ number_format(($info ?? 0), 2) }}</td>
                                                    </tr>
                                                    @else
                                                    <tr>
                                                        <td colspan="2" style="border:0;"></td>
                                                        <td><b>{{$action}}</b></td>
                                                        <td>${{ number_format(($info ?? 0), 2) }}</td>
                                                    </tr>
                                                    @endif
                                                    @endforeach
                                                </tbody>
                                            </table>

                                            <br>
                                            <p><b>Información de cobros por acciones por modelos:</b></p>

                                            <table class="table align-middle text-center">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Modelo</th>
                                                        <th scope="col">MOV</th>
                                                        <th scope="col">CONTROL</th>
                                                        <th scope="col">CUM</th>
                                                        <th scope="col">SCUM</th>
                                                        <th scope="col">XCUM</th>
                                                        <th scope="col">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($item["CobrosModelos"] as $modelo=>$info)
                                                    @if($modelo!="Total")
                                                    <tr>
                                                        <td><b>{{$modelo}}</b></td>
                                                        <td>${{ number_format(($info["MOV"] ?? 0), 2) }}</td>
                                                        <td>${{ number_format(($info["CONTROL"] ?? 0), 2) }}</td>
                                                        <td>${{ number_format(($info["CUM"] ?? 0), 2) }}</td>
                                                        <td>${{ number_format(($info["SCUM"] ?? 0), 2) }}</td>
                                                        <td>${{ number_format(($info["XCUM"] ?? 0), 2) }}</td>
                                                        <td>${{ number_format(($info["Total"] ?? 0), 2) }}</td>
                                                    </tr>
                                                    @endif
                                                    @endforeach
                                                    <tr>
                                                        <td><b>Total</b></td>
                                                        <td>${{ number_format(($item["CobrosTotales"]["MOV"] ?? 0), 2) }}</td>
                                                        <td>${{ number_format(($item["CobrosTotales"]["CONTROL"] ?? 0), 2) }}</td>
                                                        <td>${{ number_format(($item["CobrosTotales"]["CUM"] ?? 0), 2) }}</td>
                                                        <td>${{ number_format(($item["CobrosTotales"]["SCUM"] ?? 0), 2) }}</td>
                                                        <td>${{ number_format(($item["CobrosTotales"]["XCUM"] ?? 0), 2) }}</td>
                                                        <td><b>${{ number_format(($item["CobrosTotales"]["Total"] ?? 0), 2) }}</b></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            @endif

                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            @endif
                        @endforeach
                        @else
                        ERROR GENERANDO
                        @endif
                    </div>
                </div>
                @if (str_contains(url()->full(), 'localhost'))
                <div class="col-md-12 mt-2 d-grid">
                    <button class="btn btn-outline-primary" wire:click="enviarTodosReportes()">Enviar todos los reportes</button>
                </div>
                @endif
                @endif

            </div>
        </div>
    </div>


    <br>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('abrir-reporte', (event) => {
                // Aquí accedemos a los datos necesarios
                const url = event[0]["url"];  // La URL base sin parámetros
                const data = event[0]["data"];
                // const otherData = event[0]["otherData"];  // Si tienes otros datos
    
                // Creamos el formulario
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;  // Usamos la URL base
                form.target = '_blank';  // Abre en nueva ventana

                // Campo CSRF
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);

                // Campo title (ahora como POST)
                const dataInput = document.createElement('input');
                dataInput.type = 'hidden';
                dataInput.name = 'data';
                dataInput.value = data;
                form.appendChild(dataInput);

                // Enviamos el formulario
                document.body.appendChild(form);
                form.submit();
                form.remove();
            });
        });
    </script>


</div>