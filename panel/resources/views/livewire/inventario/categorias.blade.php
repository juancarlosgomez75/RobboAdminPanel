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
            @if(!$categorias->isEmpty())
            @foreach ($categorias as $categoria)
            <tr>
                <th scope="row">1</th>
                <td>Mark</td>
                <td><a type="button" class="btn btn-outline-secondary btn-sm">Eliminar</a></td>
            </tr>
            @endforeach

            @else
            <tr class="text-center">
                <td colspan="3">Sin categorias registradas aún</td>
            </tr>
            @endif
        </tbody>
    </table>

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
                    <input type="email" class="form-control" placeholder="Ejemplo: Juguetes" wire:model="name">
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
    
    
</div>
