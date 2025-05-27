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
                    <h5 class="card-title">Registro de máquina</h5>
                    <p class="card-text">Por favor ingresa la información solicitada para registrar la máquina</p><br>
                </div>
                <div class="col-md-12">
                    
                    <div class="row">
                        <div class="col-md-3">
                            <label for="hardname" class="form-label">Id de Hardware</label>
                            <input type="number" class="form-control" id="hardname" aria-describedby="hardnameHelp" placeholder="Ejemplo: 200001" wire:model="hardwareid" wire:model="hardwareid">
                            <br>
                        </div>
                        <div class="col-md-4">
                            <label for="tiponame" class="form-label">Tipo de dispositivo</label>
                            <select class="form-select" id="tiponame" wire:model="tipoid">
                                <option selected disabled value="0">Seleccionar un tipo</option>
                                <option value="1">Máquina</option>
                                <option value="2">Dildo</option>
                                <option value="3">CumSystem</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <label for="tiponame" class="form-label">Estudio asignado</label>
                            <select class="form-select" id="tiponame" wire:model="estudioid">
                                <option selected disabled value="0">Seleccionar un estudio</option>

                                <?php if(!empty($information)): ?>
                                <?php $__currentLoopData = $information; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($info["Id"]); ?>"><?php echo e($info["FullName"]); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </select>
                        </div>
                        <div class="col-md-12 text-center">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                Registrar máquina
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


</div><?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views\livewire\maquinas\create.blade.php ENDPATH**/ ?>