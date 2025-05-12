<div>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item "><a href="{{route("estudios.index")}}" class="text-secondary">Estudios</a></li>
            <li class="breadcrumb-item active" aria-current="page">Estudio</li>
        </ol>
    </nav>
    {{-- {{json_encode($loadedModels)}} --}}
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
                    
                </div>
                <div class="col-md-12">
                    <br>
                    <h5 class="card-title">Managers asociados</h5>
                    <p class="card-text">Estas son las personas registradas que gestionan el estudio</p><br>

                    <table class="table">
                        <thead>
                            <tr class="align-middle">
                                <th scope="col">Nombre</th>
                                <th scope="col">Contacto</th>
                                <th scope="col">Email</th>
                                <th scope="col"></th>
                                <th scope="col" style="width: 12%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($managers))
                            @foreach($managers as $index => $manager)
                                <tr class="align-middle">
                                    <td>{{ $manager["Name"] ?? 'Sin Nombre' }}</td>
                                    <td>{{ $manager["Phone"] ?? 'No especificado' }}</td>
                                    <td>{{ $manager["Email"] ?? 'No especificado' }}</td>
                                    <td>
                                        @if($manager["Activo"] )
                                        <i class="fa-solid fa-circle-check" style="color: green;"></i>
                                        @else
                                        <i class="fa-solid fa-circle-xmark" style="color: red;"></i>
                                        @endif
                                    </td>
                                    <td>
                                        <a type="button" class="btn btn-outline-primary btn-sm" href="manager/{{ $manager['Id'] }}">Visualizar</a>
                                    </td>
                                </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="4" class="text-center">
                                    Sin managers registrados
                                </td>
                            </tr>
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
                    <h5 class="card-title">Modelos registrados</h5>
                    <p class="card-text">Estos son los modelos registrados en este estudio</p><br>
                    <table class="table">
                        <thead>
                            <tr class="align-middle">
                                <th scope="col">#</th>
                                <th scope="col" style="cursor: pointer;" wire:click="ordenarModelosBy('manager')">
                                    @if($ordenarModelosPor=="manager")
                                    @if($ordenarModelosDesc)
                                        <a  class="text-decoration-none text-dark"> 
                                            <i class="fa-solid fa-angle-down me-2"></i>
                                        </a>
                                    @else
                                        <a class="text-decoration-none text-dark"> 
                                            <i class="fa-solid fa-angle-up me-2"></i>
                                        </a>
                                    @endif
                                    @endif
                                    Manager
                                </th>
                                <th scope="col" style="cursor: pointer;" wire:click="ordenarModelosBy('user')">
                                    @if($ordenarModelosPor=="user")
                                    @if($ordenarModelosDesc)
                                        <a  class="text-decoration-none text-dark"> 
                                            <i class="fa-solid fa-angle-down me-2"></i>
                                        </a>
                                    @else
                                        <a class="text-decoration-none text-dark"> 
                                            <i class="fa-solid fa-angle-up me-2"></i>
                                        </a>
                                    @endif
                                    @endif
                                    Username
                                </th>
                                <th scope="col">Páginas</th>
                                <th scope="col" style="width: 15%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($modelosOrdenados))
                            @foreach ($modelosOrdenados as $Model)
                            <tr>
                                <td>{{$Model["ModelId"]}}</td>
                                <td>{{$Model["manager_name"]}}</td>
                                <td>{{$Model["ModelUserName"]}}</td>
                                <td>
                                    @if(!empty($Model["ModelPages"]))
                                    @foreach ($Model["ModelPages"] as $index => $Page)
                                    <span>{{ ucfirst(strtolower($Page["NickPage"])) }}</span>
                                    @if (!$loop->last) | @endif
                                    @endforeach
                                    @else
                                    <span>Sin páginas</span>
                                    @endif
                                </td>
                                <td>
                                    <a type="button" class="btn btn-outline-primary btn-sm" href="{{route("modelo.viewedit",$Model["ModelId"])}}">Visualizar</a>
                                </td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="4" class="text-center">
                                    Sin modelos registrados
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6 text-center">
                    <a type="button" class="btn btn-outline-secondary" href="{{route("modelos.create",$informacion["Id"])}}">
                        Crear modelo
                    </a>
                    
                </div>
                <div class="col-md-5 text-center">
                    
                        <form wire:submit.prevent="importCsv">
                            <div class="input-group">
                            <input class="form-control" type="file" wire:model.change="csv_file" accept=".csv">
                            <button class="btn btn-outline-secondary" type="submit" id="inputGroupFileAddon04">Subir CSV</button>
                            </div>
                        </form>
                    
                </div>
                <div class="col-md-12">
                    <br>
                    <h5 class="card-title">Máquinas asociadas</h5>
                    <p class="card-text">Estas son las máquinas que se encuentran registradas para este estudio</p><br>

                    <table class="table">
                        <thead>
                            <tr class="align-middle">
                                <th scope="col">#</th>
                                <th scope="col">Firmware</th>
                                <th scope="col">Tipo</th>
                                <th scope="col" style="width: 28%;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($maquinas))
                            @foreach($maquinas as $index => $maquina)
                                <tr class="align-middle">
                                    <td>{{ $maquina["ID"] ?? 'N/R' }}</td>
                                    <td>{{ $maquina["FirmwareID"] ?? 'N/E' }}</td>
                                    <td>{{ $maquina["Tipo"] ?? 'No especificado' }}</td>
                                    
                                    <td>
                                        <a type="button" class="btn btn-outline-primary btn-sm" href="{{route("maquinas.view",$maquina["ID"])}}">Visualizar</a>
                                        @if($informacion["Id"]!=1)
                                        <a type="button" class="btn btn-outline-danger btn-sm" wire:click="desvincular({{$index}})">Desvincular</a>
                                        @endif
                                    </td>
                                    
                                </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="4" class="text-center">
                                    Sin máquinas asociadas
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 text-center">
                    <a type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#vincularMachine">
                        Vincular máquina
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="vincularMachine" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="vincularMachineLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h1 class="modal-title fs-5" id="vincularMachineLabel">Vincular máquina con estudio</h1>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    Al vincular una máquina con este estudio, permites que el estudio pueda hacer uso de ella. Por favor ingresa el firmware id de la máquina para moverla.
                </p>
                <div class="mb-3">
                    <label class="form-label">Firmware Id</label>
                    <input type="number" class="form-control" placeholder="Ejemplo: 100000" wire:model="moveFirmwareId" min="100000" max="999999">
                </div>
            </div>
            <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:click="moveMachine()">Vincular máquina</button>
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

    <!-- Modal Bootstrap -->
    <div class="modal fade" id="moveModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Confirmación de vinculación</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p>
                        Actualmente la máquina con Firmware #<b>{{$moveFirmwareId}}</b> está vinculada con el estudio <b>{{$estudioMoveInfo["StudyName"] ?? "No encontrado"}}</b> , ¿Desea confirmar la vinculación?
                    </p>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:click="confirmarVinculacion()">Confirmar vinculación</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Bootstrap -->
    <div class="modal fade" id="saveModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Registro masivo de modelos</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p>
                        A continuación se presenta la información almacenada por cada modelo, por favor verifica y si todo está bien, presiona en guardar para iniciar el proceso de almacenamiento. <br>
                        Los modelos ingresados serán asignados al primer manager registrado, si no hay, el sistema generará error.
                    </p>

                    @foreach($loadedModels as $model)
                    <div class="row mb-2">
                        <div class="col-12">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <th>Username</th>
                                    <th>Customname</th>
                                    <th>Usar custom</th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{$model["Username"]}}</td>
                                        <td>{{$model["Customname"]}}</td>
                                        <td>
                                            @if($model["UseCustom"])
                                            Usarlo
                                            @else
                                            No Usarlo
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3">
                                            <table class="table mb-0 table-borderless text-center">
                                                <thead>
                                                    <tr>
                                                        @foreach (array_keys($model['Pages']) as $header)
                                                            <th>{{ $header }}</th>
                                                        @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        @foreach ($model['Pages'] as $valor)
                                                            <td>{{ $valor }}</td>
                                                        @endforeach
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>

                    @endforeach

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:click="guardarModelos()">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Bootstrap -->
    <div class="modal fade" id="resultsModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Resultados de registro</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p>
                        Estos fueron los resultados del proceso de registro. Para visualizarlos, refresque la página.
                    </p>

                    <table class="table">
                        <thead>
                            <th>Modelo</th>
                            <th>Resultado</th>
                            <th>Observaciones</th>
                        </thead>
                        <tbody>
                            @foreach($loadedResults as $modelo => $resultado)
                            <tr>
                                <td>{{ $modelo }}</td>
                                <td>
                                    @if($resultado["Valor"])
                                        <span class="badge bg-success">Registrado</span>
                                    @else
                                        <span class="badge bg-danger">No registrado</span>
                                    @endif
                                </td>
                                <td>
                                    {{$resultado["Observaciones"]}}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        Livewire.on('abrirModalSave', () => {
            let modal = new bootstrap.Modal(document.getElementById('saveModal'));
            modal.show();
        });

        Livewire.on('cerrarModalSave', () => {
            let modalEl = document.getElementById('saveModal');
            let modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        });

        Livewire.on('abrirModalResults', () => {
            let modal = new bootstrap.Modal(document.getElementById('resultsModal'));
            modal.show();
        });

        Livewire.on('cerrarModalResults', () => {
            let modalEl = document.getElementById('resultsModal');
            let modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        });

    });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            Livewire.on('abrirModalMove', () => {
                let modal = new bootstrap.Modal(document.getElementById('moveModal'));
                modal.show();
            });
    
            Livewire.on('cerrarModalMove', () => {
                let modalEl = document.getElementById('moveModal');
                let modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            });
        });
    </script>


    <br>


</div>