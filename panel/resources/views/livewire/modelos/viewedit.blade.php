<div>
    {{-- {{json_encode($ModelInformation)}} --}}
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item "><a href="{{route("estudio.ver",$estudioactual)}}" class="text-secondary">Estudio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Modelo</li>
        </ol>
    </nav>
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h5 class="card-title">Información del modelo</h5>
                    <p class="card-text">Esta es la información almacenada actualmente para este modelo</p><br>
                </div>
                <div class="col-md-12">
                    
                    <div class="row">
                        <div class="col-md-5">
                            <label for="manname" class="form-label">Nombre de usuario</label>
                            <input type="text" class="form-control" id="manname" aria-describedby="mannameHelp" placeholder="Ejemplo: labtest" wire:model="drivername" @if(!$editing) disabled @endif>
                            <div id="mannameHelp" class="form-text">Con el que se loguea en el driver</div>
                            <br>
                        </div>
                        <div class="col-md-4">
                            <label for="customname" class="form-label">Mensaje personalizado</label>
                            <input type="text" class="form-control" id="customname" aria-describedby="customnameHelp" placeholder="Ejemplo: Labtesito" wire:model="customname" @if(!$editing) disabled @endif>
                            <div id="customnameHelp" class="form-text">Con el que el driver saluda si decides usarlo.</div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">¿Usarlo?</label>
                            <select class="form-select" @if(!$editing) disabled @endif wire:model.live="usecustomname">
                                <option disabled value="-1">Seleccionar una opción</option>
                                <option value="0">No</option>
                                <option value="1">Si</option>
                            </select>

                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Estudio actual</label>
                            <select class="form-select" @if(!$editing) disabled @endif wire:model.live="estudioactual">
                                
                                @if(!empty($listadoestudios))
                                <option disabled value="0">Seleccionar un estudio</option>
                                @foreach($listadoestudios as $estudio)
                                @if($estudio["Active"])
                                <option value="{{$estudio["Id"]}}">{{$estudio["FullName"]}}</option>
                                @endif
                                @endforeach
                                @else
                                <option disabled value="0"></option>
                                @endif
                            </select>
                            <br>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Manager actual</label>
                            <select class="form-select" @if(!$editing) disabled @endif wire:model="manageractual">
                                
                                @if(!empty($listadomanagers))
                                <option disabled value="0">Seleccionar un manager</option>
                                @foreach($listadomanagers as $manager)
                                <option value="{{$manager["Id"]}}">{{$manager["Name"]}}</option>
                                @endforeach
                                @else
                                <option disabled value="0"></option>
                                @endif
                            </select>
                            <br>
                        </div>
                        
                    </div>
                    
                </div>

                <div class="col-md-9">
                    <h5 class="card-title">Páginas que usa</h5>
                    <p class="card-text">Estas son las páginas que el modelo indica que usa</p><br>
                </div>
                
                <div class="col-md-3 text-end pe-4">
                    <br><br>
                    @if($editing)
                    <button type="button" class="btn btn-outline-secondary" wire:click="nuevaPagina()">
                        Añadir página
                    </button>
                    @endif
                </div>
                <div class="col-md-12">
                    
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="w-10" scope="col" style="width: 7%;">#</th>
                                <th class="w-40" scope="col">Página</th>
                                <th class="w-30" scope="col">Nickname en página</th>
                                <th scope="col" style="width: 10%;"></th>
                            </tr>
                        </thead>
                        <tbody class="align-middle">
                            @if(!empty($paginas))
                            @php
                                $paginasUsadas = [];

                                foreach($paginas as $indice => $Page){
                                    $paginasUsadas[] = $Page['NickPage'];
                                }
                            @endphp

                            @foreach ($paginas as $indice => $Page)
                                @php
                                     $seleccionActual = $Page['NickPage'] ?? '-1';
                                    
                                @endphp

                                <tr>
                                    <td>{{ $indice + 1 }}</td>
                                    <td>
                                        <select class="form-select" wire:model.live="paginas.{{ $indice }}.NickPage" @if(!$editing) disabled @endif>
                                            <option disabled value="-1">Selecciona una página</option>
                                            @foreach ($paginasDisponibles as $pag)
                                                @if (!in_array($pag, $paginasUsadas) || $pag === $seleccionActual)
                                                    <option value="{{ $pag }}">{{ $pag }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" wire:model="paginas.{{ $indice }}.NickName" placeholder="Ejemplo: usuario.chatb" @if(!$editing) disabled @endif>
                                    </td>
                                    <td>
                                        <a class="btn btn-outline-danger btn-sm" wire:click="eliminarPagina({{ $indice }})">
                                            Eliminar
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="4" class="text-center">
                                    Sin páginas registradas
                                </td>
                            </tr>
                            @endif


                        </tbody>
                    </table>
                    
                    
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

                    @if($active)
                    <button type="button" class="btn btn-outline-danger ms-2" wire:click="desactivarUsuario()">
                        Desactivar modelo
                    </button>
                    @else
                    <button type="button" class="btn btn-outline-success ms-2" wire:click="activarUsuario()">
                        Activar modelo
                    </button>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirmación de modificación</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    Al presionar en confirmar cambios, confirma que la información aquí contenida es correcta.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Regresar</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:click="guardarEdicion" >Confirmar cambios</button>
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