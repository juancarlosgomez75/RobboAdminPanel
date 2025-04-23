<div>
    {{json_encode($resultado)}}
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <h4 class="card-title">Generación de reportes</h4>
                    <p class="card-text">Desde aquí podrás generar los reportes que desees. Para iniciar, por favor ingresa un rango de fechas y selecciona el estudio.</p>
                </div>
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
                    <button type="button" class="btn btn-primary" wire:click="generarReporte()">Generar reporte</button>
                </div>

                {{-- <div class="col-md-12">
                    
                    <div class="row">
                        <div class="col-md-4">
                            <label for="studyname" class="form-label">Nombre del estudio</label>
                            <input type="text" class="form-control" id="studyname" aria-describedby="studynameHelp" placeholder="Ejemplo: Robbocock Medellin" wire:model="nombre" required >
                            <div id="studynameHelp" class="form-text">Si hay varias sedes, indica también la sede.</div>
                            <br>
                        </div>
                        <div class="col-md-4">
                            <label for="socialname" class="form-label">Razón social</label>
                            <input type="text" class="form-control" id="socialname" aria-describedby="socialnameHelp" placeholder="Ejemplo: Coolsoft Technology" wire:model="razonsocial" required >
                            <div id="socialnameHelp" class="form-text">Nombre jurídico</div>
                            <br>
                        </div>
                        <div class="col-md-4">
                            <label for="nitname" class="form-label">NIT</label>
                            <input type="number" class="form-control" id="nitname" aria-describedby="nitnameHelp" placeholder="Documento legal de la empresa" wire:model="nit" required min="0" >
                        </div>
                        <div class="col-md-5">
                            <label for="cityname" class="form-label">Ciudad</label>
                            <select class="form-select" id="cityname" aria-label="cityHelp" wire:model="idciudad" required >
                                <option disabled value="0">Selecciona la ciudad y pais del estudio</option>
                                @foreach($ciudades as $index => $ciudad)
                                <option value="{{$ciudad["Id"]}}">{{$ciudad["Name"]}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-7">
                            <label for="addressname" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="addressname" aria-describedby="addressnameHelp" placeholder="Ejemplo: Carrera 49 #61 Sur - 540 Bodega 177" wire:model="direccion" required >
                            <div id="addressnameHelp" class="form-text">En dónde está ubicada la sede, barrio, y tipo de domicilio</div>
                            <br>
                        </div>
                        <div class="col-md-7">
                            <label for="studyname" class="form-label">Nombre del responsable</label>
                            <input type="text" class="form-control" id="studyname" aria-describedby="studynameHelp" placeholder="Ej: Pepito Perez" wire:model="responsable" required >
                            <div id="studynameHelp" class="form-text">Es el representante/manager del estudio</div>
                        </div>
                        <div class="col-md-5">
                            <label for="phonename" class="form-label">Número de contacto</label>
                            <input type="text" class="form-control" id="phonename" aria-describedby="phonenameHelp" placeholder="Ej: +573005696354" wire:model="telcontacto" required >
                            <div id="phonenameHelp" class="form-text">Número al que se pueda comunicar con el responsable</div>
                            <br>
                        </div>
                        <div class="col-md-5">
                            <label for="phonename" class="form-label">Número de contacto 2</label>
                            <input type="text" class="form-control" id="phonename" aria-describedby="phonenameHelp" placeholder="Ej: +573005696354" wire:model="telcontacto2" required >
                            <div id="phonenameHelp" class="form-text">Número secundario del responsable</div>
                            <br>
                        </div>
                        <div class="col-md-7">
                            <label for="emailname" class="form-label">Email de contacto</label>
                            <input type="email" class="form-control" id="emailname" aria-describedby="emailnameHelp" placeholder="Ej: estudio@estudio.com" wire:model="email" required >
                            <div id="emailnameHelp" class="form-text">Correo al que llegarán los soportes</div>
                            <br>
                        </div>
                        <div class="col-md-12 text-center">
                            @if(!$editing)
                            <button type="button" class="btn btn-outline-primary" wire:click="activarEdicion">
                                Editar información
                            </button>
                            @else
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                Guardar cambios
                            </button>
                            @endif

                            @if($activo)
                            <button type="button" class="btn btn-outline-danger ms-2" wire:click="desactivarEstudio()">
                                Desactivar estudio
                            </button>
                            @else
                            <button type="button" class="btn btn-outline-success ms-2" wire:click="activarEstudio()">
                                Activar estudio
                            </button>
                            @endif

                        </div>
                        
                    </div>
                    
                </div> --}}
            </div>
        </div>
    </div>


    <br>


</div>