<div>
    <?php if($alerta): ?>
        <?php if($alerta_sucess!=""): ?>
        <div class="alert alert-success" role="alert">
            <?php echo e($alerta_sucess); ?>

        </div>
        <?php elseif($alerta_error!=""): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo e($alerta_error); ?>

        </div>
        <?php elseif($alerta_warning!=""): ?>
        <div class="alert alert-warning" role="alert">
            <?php echo e($alerta_warning); ?>

        </div>
        <?php endif; ?>
    <?php endif; ?>
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h5 class="card-title">Crear estudio</h5>
                    <p class="card-text">Por favor completa toda la información para registrar el estudio</p><br>
                </div>
                <div class="col-md-12">
                    
                    <div class="row">
                        <div class="col-md-4">
                            <label for="studyname" class="form-label">Nombre del estudio</label>
                            <input type="text" class="form-control" id="studyname" aria-describedby="studynameHelp" placeholder="Ejemplo: Robbocock Medellin" wire:model="nombre" required>
                            <div id="studynameHelp" class="form-text">Si hay varias sedes, indica también la sede.</div>
                            <br>
                        </div>
                        <div class="col-md-4">
                            <label for="socialname" class="form-label">Razón social</label>
                            <input type="text" class="form-control" id="socialname" aria-describedby="socialnameHelp" placeholder="Ejemplo: Coolsoft Technology" wire:model="razonsocial" required>
                            <div id="socialnameHelp" class="form-text">Nombre jurídico</div>
                            <br>
                        </div>
                        <div class="col-md-4">
                            <label for="nitname" class="form-label">NIT</label>
                            <input type="number" class="form-control" id="nitname" aria-describedby="nitnameHelp" placeholder="Documento legal de la empresa" wire:model="nit" required min="0">
                        </div>
                        <div class="col-md-5">
                            <label for="cityname" class="form-label">Ciudad</label>
                            <select class="form-select" id="cityname" aria-label="cityHelp" wire:model="idciudad" required>
                                <option selected disabled value="0">Selecciona la ciudad y pais del estudio</option>
                                <?php $__currentLoopData = $Ciudades; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $ciudad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($ciudad["Id"]); ?>"><?php echo e($ciudad["Name"]); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-7">
                            <label for="addressname" class="form-label">Dirección</label>
                            <input type="text" class="form-control" id="addressname" aria-describedby="addressnameHelp" placeholder="Ejemplo: Carrera 49 #61 Sur - 540 Bodega 177" wire:model="direccion" required>
                            <div id="addressnameHelp" class="form-text">En dónde está ubicada la sede, barrio, y tipo de domicilio</div>
                            <br>
                        </div>
                        <div class="col-md-7">
                            <label for="studyname" class="form-label">Nombre del responsable</label>
                            <input type="text" class="form-control" id="studyname" aria-describedby="studynameHelp" placeholder="Ej: Pepito Perez" wire:model="responsable" required>
                            <div id="studynameHelp" class="form-text">Es el representante/manager del estudio</div>
                        </div>
                        <div class="col-md-5">
                            <label for="phonename" class="form-label">Número de contacto 1</label>
                            <input type="text" class="form-control" id="phonename" aria-describedby="phonenameHelp" placeholder="Ej: +573005696354" wire:model="telcontacto" required>
                            <div id="phonenameHelp" class="form-text">Número principal del responsable</div>
                            <br>
                        </div>
                        <div class="col-md-5">
                            <label for="phonename" class="form-label">Número de contacto 2</label>
                            <input type="text" class="form-control" id="phonename" aria-describedby="phonenameHelp" placeholder="Ej: +573005696354" wire:model="telcontacto2">
                            <div id="phonenameHelp" class="form-text">Número secundario del responsable</div>
                            <br>
                        </div>
                        <div class="col-md-7">
                            <label for="emailname" class="form-label">Email de contacto</label>
                            <input type="email" class="form-control" id="emailname" aria-describedby="emailnameHelp" placeholder="Ej: estudio@estudio.com" wire:model="email">
                            <div id="emailnameHelp" class="form-text">Correo al que llegarán los soportes</div>
                            <br>
                        </div>
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                Registrar estudio en base de datos
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
                    Al presionar en registrar, confirma que la información aquí contenida es correcta.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Regresar</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:click="registrar" >Confirmar registro</button>
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


</div><?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views\livewire\estudios\create.blade.php ENDPATH**/ ?>