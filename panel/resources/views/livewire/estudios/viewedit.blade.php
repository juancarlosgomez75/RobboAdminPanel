<div>
    @if($alerta)
        @if($alerta_sucess!="")
        <div class="alert alert-success" role="alert">
            {{$alerta_sucess}}
        </div>
        @elseif($alerta_error!="")
        <div class="alert alert-danger" role="alert">
            {{$alerta_error}}
        </div>
        @elseif($alerta_warning!="")
        <div class="alert alert-warning" role="alert">
            {{$alerta_warning}}
        </div>
        @endif
    @endif
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h5 class="card-title">Información general de estudio</h5>
                    <p class="card-text">Esta es la información almacenada actualmente para este estudio</p><br>
                </div>
                <div class="col-md-12">
                    
                    <div class="row">
                        <div class="col-md-4">
                            <label for="studyname" class="form-label">Nombre del estudio</label>
                            <input type="text" class="form-control" id="studyname" aria-describedby="studynameHelp" placeholder="Ejemplo: Robbocock Medellin" wire:model="nombre" required @if(!$editing) disabled @endif>
                            <div id="studynameHelp" class="form-text">Si hay varias sedes, indica también la sede.</div>
                            <br>
                        </div>
                        <div class="col-md-4">
                            <label for="socialname" class="form-label">Razón social</label>
                            <input type="text" class="form-control" id="socialname" aria-describedby="socialnameHelp" placeholder="Ejemplo: Coolsoft Technology" wire:model="razonsocial" required @if(!$editing) disabled @endif>
                            <div id="socialnameHelp" class="form-text">Nombre jurídico</div>
                            <br>
                        </div>
                        <div class="col-md-4">
                            <label for="nitname" class="form-label">NIT</label>
                            <input type="number" class="form-control" id="nitname" aria-describedby="nitnameHelp" placeholder="Documento legal de la empresa" wire:model="nit" required min="0" @if(!$editing) disabled @endif>
                        </div>
                        <div class="col-md-5">
                            <label for="cityname" class="form-label">Ciudad</label>
                            <select class="form-select" id="cityname" aria-label="cityHelp" wire:model="idciudad" required @if(!$editing) disabled @endif>
                                <option disabled value="0">Selecciona la ciudad y pais del estudio</option>
                                @foreach($ciudades as $index => $ciudad)
                                <option value="{{$ciudad["Id"]}}">{{$ciudad["Name"]}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-7">
                            <label for="addressname" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="addressname" aria-describedby="addressnameHelp" placeholder="Ejemplo: Carrera 49 #61 Sur - 540 Bodega 177" wire:model="direccion" required @if(!$editing) disabled @endif>
                            <div id="addressnameHelp" class="form-text">En dónde está ubicada la sede, barrio, y tipo de domicilio</div>
                            <br>
                        </div>
                        <div class="col-md-7">
                            <label for="studyname" class="form-label">Nombre del responsable</label>
                            <input type="text" class="form-control" id="studyname" aria-describedby="studynameHelp" placeholder="Ej: Pepito Perez" wire:model="responsable" required @if(!$editing) disabled @endif>
                            <div id="studynameHelp" class="form-text">Es el representante/manager del estudio</div>
                        </div>
                        <div class="col-md-5">
                            <label for="phonename" class="form-label">Número de contacto</label>
                            <input type="text" class="form-control" id="phonename" aria-describedby="phonenameHelp" placeholder="Ej: +573005696354" wire:model="telcontacto" required @if(!$editing) disabled @endif>
                            <div id="phonenameHelp" class="form-text">Número al que se pueda comunicar con el responsable</div>
                            <br>
                        </div>
                        <div class="col-md-5">
                            <label for="phonename" class="form-label">Número de contacto 2</label>
                            <input type="text" class="form-control" id="phonename" aria-describedby="phonenameHelp" placeholder="Ej: +573005696354" wire:model="telcontacto2" required @if(!$editing) disabled @endif>
                            <div id="phonenameHelp" class="form-text">Número secundario del responsable</div>
                            <br>
                        </div>
                        <div class="col-md-7">
                            <label for="emailname" class="form-label">Email de contacto</label>
                            <input type="email" class="form-control" id="emailname" aria-describedby="emailnameHelp" placeholder="Ej: estudio@estudio.com" wire:model="email" required @if(!$editing) disabled @endif>
                            <div id="emailnameHelp" class="form-text">Correo al que llegarán los soportes</div>
                            <br>
                        </div>
                        <div class="col-md-12 text-center">
                            @if(!$editing)
                            <button type="button" class="btn btn-outline-primary" wire:click="activarEdicion">
                                Editar información básica
                            </button>
                            @else
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                Guardar cambios
                            </button>
                            @endif
                        </div>
                        
                    </div>
                    
                </div>
                <div class="col-md-12">
                    <br>
                    <h5 class="card-title">Managers asociados</h5>
                    <p class="card-text">Estas son las personas registradas que gestionan el estudio</p><br>

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Nombre</th>
                                <th scope="col">Contacto</th>
                                <th scope="col">Email</th>
                                <th scope="col" style="width: 12%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($managers))
                            @foreach($managers as $index => $manager)
                                <tr>
                                    <td>{{ $manager["Name"] ?? 'Sin Nombre' }}</td>
                                    <td>{{ $manager["Phone"] ?? 'No especificado' }}</td>
                                    <td>{{ $manager["Email"] ?? 'No especificado' }}</td>
                                    <td>
                                        <a type="button" class="btn btn-outline-primary btn-sm" href="manager/{{ $manager['Id'] }}">Visualizar</a>
                                    </td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 text-center">
                    {{-- <a type="button" class="btn btn-outline-secondary" href="manager/crear/{{$estudioactual}}"> --}}
                    <a type="button" class="btn btn-outline-secondary" href="manager/crear/{{$informacion["Id"]}}">
                        Crear nuevo manager
                    </a>
                </div>
                <div class="col-md-12">
                    <br>
                    <h5 class="card-title">Máquinas asociadas</h5>
                    <p class="card-text">Estas son las máquinas que se encuentran registradas para este estudio</p><br>

                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Firmware</th>
                                <th scope="col">Tipo</th>
                                <th scope="col" style="width: 12%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($maquinas))
                            @foreach($maquinas as $index => $maquina)
                                <tr>
                                    <td>{{ $maquina["ID"] ?? 'N/R' }}</td>
                                    <td>{{ $maquina["FirmwareID"] ?? 'N/E' }}</td>
                                    <td>{{ $maquina["Tipo"] ?? 'No especificado' }}</td>
                                    <td>
                                        <a type="button" class="btn btn-outline-danger btn-sm">Desvincular</a>
                                    </td>
                                </tr>
                            @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 text-center">
                    {{-- <a type="button" class="btn btn-outline-secondary" href="manager/crear/{{$estudioactual}}"> --}}
                    <a type="button" class="btn btn-outline-secondary" href="manager/crear/">
                        Crear nuevo manager
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirmación de edición</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    Al presionar en confirmar modificaciones, confirma que la información aquí contenida es correcta.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Regresar</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:click="modificar" >Confirmar modificaciones</button>
            </div>
        </div>
        </div>
    </div>

  <script>
    const myModal = document.getElementById('myModal')
    const myInput = document.getElementById('myInput')

    myModal.addEventListener('shown.bs.modal', () => {
    myInput.focus()
    })
</script>
    <br>


</div>