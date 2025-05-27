<div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Nombre de categoría</th>
                <th scope="col" style="width: 15%"></th>
            </tr>
        </thead>
        <tbody class="align-center">
            <!--[if BLOCK]><![endif]--><?php if(!$categorias->isEmpty()): ?>
            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $categorias; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoria): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <th scope="row"><?php echo e($categoria->id); ?></th>
                <td><?php echo e($categoria->name); ?></td>
                <td>
                    <!--[if BLOCK]><![endif]--><?php if(auth()->check() && auth()->user()->rank >= 4): ?>
                    <a type="button" class="btn btn-outline-secondary btn-sm" wire:click="editar(<?php echo e($categoria->id); ?>)">Editar</a>
                    <a type="button" class="btn btn-outline-danger btn-sm" wire:click="eliminar(<?php echo e($categoria->id); ?>)">Eliminar</a>
                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

            <?php else: ?>
            <tr class="text-center">
                <td colspan="3">Sin categorias registradas aún</td>
            </tr>
            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
        </tbody>
    </table>

    <!--[if BLOCK]><![endif]--><?php if(auth()->check() && auth()->user()->rank >= 4): ?>
    <center><a class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#createCategory">Crear nueva categoría</a></center>

    <!-- Modal -->
    <div class="modal fade" id="createCategory" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="createCategoryLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="createCategoryLabel">Creación de nueva categoría</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    Por favor ingresa la siguiente información para registrar una nueva categoría en el sistema
                </p>

                <div class="mb-3">
                    <label class="form-label">Nombre de la categoría</label>
                    <input type="text" class="form-control" placeholder="Ejemplo: Juguetes" wire:model="name">
                </div>
                <div class="mb-3">
                    <labelclass="form-label">Descripción</label>
                    <textarea class="form-control" rows="2" placeholder="Aquí describe de forma corta qué productos estarán contenidos en esta categoría" wire:model="description"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:click="guardar()">Crear categoría</button>
            </div>
            </div>
        </div>
    </div>

    <!-- Modal Bootstrap -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Edición de categoría</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p>
                        Al presionar en guardar, la información será almacenada automáticamente
                    </p>

                    <div class="mb-3">
                        <label class="form-label">Nombre de la categoría</label>
                        <input type="email" class="form-control" placeholder="Ejemplo: Juguetes" wire:model="name_edit">
                    </div>
                    <div class="mb-3">
                        <labelclass="form-label">Descripción</label>
                        <textarea class="form-control" rows="2" placeholder="Aquí describe de forma corta qué productos estarán contenidos en esta categoría" wire:model="description_edit"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" wire:click="guardarCambios()">Guardar cambios</button>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

<script>
    document.addEventListener("DOMContentLoaded", function () {
        Livewire.on('abrirModalEdit', () => {
            let modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
        });

        Livewire.on('cerrarModalEdit', () => {
            let modalEl = document.getElementById('editModal');
            let modal = bootstrap.Modal.getInstance(modalEl);
            if (modal) modal.hide();
        });
    });
</script>
    
    
</div>
<?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views/livewire/inventario/categorias.blade.php ENDPATH**/ ?>