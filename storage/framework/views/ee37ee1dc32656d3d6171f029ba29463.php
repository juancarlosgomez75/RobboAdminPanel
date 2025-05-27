<div>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item "><a href="<?php echo e(route("estudio.ver",$idEstudio)); ?>" class="text-secondary">Estudio</a></li>
            <li class="breadcrumb-item active" aria-current="page">Crear Manager</li>
        </ol>
    </nav>
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h5 class="card-title">Registro de manager</h5>
                    <p class="card-text">Por favor ingresa la información solicitada para registrar al manager</p><br>
                </div>
                <div class="col-md-12">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <label for="manname" class="form-label">Nombre del manager</label>
                            <input type="text" class="form-control" id="manname" aria-describedby="mannameHelp" placeholder="Ejemplo: Juan Carlos Gomez" wire:model="nombre">
                            <div id="mannameHelp" class="form-text">O la forma en que gusta que se refieran.</div>
                            <br>
                        </div>
                        <div class="col-md-3">
                            <label for="telname" class="form-label">Numero de contacto</label>
                            <input type="tel" class="form-control" id="telname" aria-describedby="telnameHelp" placeholder="Ejemplo: +573007885858" wire:model="telefono">
                            <br>
                        </div>
                        <div class="col-md-3">
                            <label for="emailname" class="form-label">Email</label>
                            <input type="email" class="form-control" id="emailname" aria-describedby="emailnameHelp" placeholder="Ejemplo: juancarlos@gmail.com" wire:model="email">
                        </div>
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                Registrar
                            </button>
                        </div>
                        
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirmación de registro</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    Al presionar en confirmar registro, confirma que la información aquí contenida es correcta.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Regresar</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:click="guardar" >Confirmar registro</button>
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


</div><?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views/livewire/estudios/manager-create.blade.php ENDPATH**/ ?>