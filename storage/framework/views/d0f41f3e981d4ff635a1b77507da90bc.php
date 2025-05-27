<div>
    <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item "><a href="<?php echo e(route("inventario.index")); ?>" class="text-secondary">Productos</a></li>
            <li class="breadcrumb-item "><a href="<?php echo e(route("inventario.viewedit",$inventory->product_info->id)); ?>" class="text-secondary">Producto</a></li>
            <li class="breadcrumb-item active" aria-current="page">Crear movimiento</li>
        </ol>
    </nav>
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h5 class="card-title">Registrar movimiento</h5>
                    <p class="card-text">Por favor completa la información para registrar un movimiento.</p>
                </div>
                <div class="col-md-6 pt-3 mb-3">
                        <label class="form-label">Razón del movimiento</label>
                        <input type="text" placeholder="Ejemplo: Envío a estudio" class="form-control" wire:model="reason">
                </div>
                <div class="col-md-3 pt-3 mb-3">
                    <label class="form-label">Cantidad</label>
                    <input type="number" min="1" step="1" class="form-control" wire:model="amount">
                </div>
                <div class="col-md-3 pt-3 mb-3">
                    <label class="form-label">Tipo de movimiento</label>
                    <select class="form-select" wire:model="type">
                        <option selected diabled value="-1">Seleccionar tipo</option>
                        <option value="1">Ingreso</option>
                        <option value="0">Retiro</option>
                    </select>
                </div>
                <div class="col-md-12 mb-3">
                    <label class="form-label">Detalles de movimiento:</label>
                    <textarea class="form-control" rows="3" wire:model="details" placeholder="Ejemplo: Se entregan N productos a X persona"></textarea>
                </div>
                <div class="col-md-12 text-center">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                        Registrar movimiento
                    </button>
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


    </div>
</div><?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views/livewire/inventario/movement.blade.php ENDPATH**/ ?>