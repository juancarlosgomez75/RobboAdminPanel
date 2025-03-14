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
                    <h5 class="card-title">Información de cuenta</h5>
                    <p class="card-text">Esta es la información almacenada actualmente para esta cuenta</p><br>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Usuario</label>
                    <input type="text" class="form-control" placeholder="Ejemplo: admin" wire:model="username" required @if(!$editing) disabled @endif>
                    <div class="form-text">Con el que se loguea. No puede haber usuarios repetidos.</div>
                    <br>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input type="text" class="form-control" placeholder="Ejemplo: Pedro" wire:model="name" required @if(!$editing) disabled @endif>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" placeholder="Ejemplo: admin@admin.com" wire:model="email" required @if(!$editing) disabled @endif>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Rango</label>
                    <select class="form-select" wire:model="rank" required @if(!$editing) disabled @endif>
                        <option selected disabled value="0">Selecciona un rango</option>
                        @foreach($rangos as $rank)
                        <option value="{{$rank["id"]}}">{{$rank["name"]}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12 text-center"><br>
                    @if(!$editing)
                    <button type="button" class="btn btn-outline-primary" wire:click="activarEdicion">
                        Editar información
                    </button>
                    @else
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        Guardar cambios
                    </button>
                    @endif

                    @if($usuario->activo)
                    <button type="button" class="btn btn-outline-danger ms-2" wire:click="desactivarUsuario()">
                        Desactivar usuario
                    </button>
                    @else
                    <button type="button" class="btn btn-outline-success ms-2" wire:click="activarUsuario()">
                        Activar usuario
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
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:click="modificar" >Confirmar cambios</button>
            </div>
        </div>
        </div>
    </div>

    <br>
</div>