<div>
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h5 class="card-title">Información de perfil</h5>
                    <p class="card-text">Esta es la información almacenada actualmente para tu perfil</p><br>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Usuario</label>
                    <input type="text" class="form-control" placeholder="Ejemplo: admin" wire:model="username" required disabled>
                    <div class="form-text">Este es el usuario con el que te logueas en este panel</div>
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
                    <label class="form-label">Rol</label>
                    <input type="text" class="form-control" wire:model="rank" required disabled>
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

                </div>
                <div class="col-md-12">
                    <h5 class="card-title">Cambiar contraseña</h5>
                    <p class="card-text">Por favor ingresa tu contraseña para poder cambiarla</p><br>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Contraseña actual</label>
                    <input type="password" class="form-control" wire:model="password" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Contraseña nueva</label>
                    <input type="password" class="form-control" wire:model="passwordnew" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Contraseña nueva de nuevo</label>
                    <input type="password" class="form-control" wire:model="passwordnewagain" required>
                </div>
                <div class="col-md-12 text-center"><br>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#changePassword">
                        Cambiar contraseña
                    </button>

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

    <div class="modal fade" id="changePassword" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="changePasswordLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="changePasswordLabel">Confirmación de cambio de contraseña</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    Al cambiar la contraseña deberás iniciar sesión nuevamente. En caso de olvidar la contraseña, deberás contactarte con un administrador para que la reinicie.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Regresar</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:click="modificarPassword" >Confirmar cambio de contraseña</button>
            </div>
        </div>
        </div>
    </div>

    <br>
</div>