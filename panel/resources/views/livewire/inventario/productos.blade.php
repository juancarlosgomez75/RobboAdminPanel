<div>
    <style>
    .custom-input {
        display: flex;
        align-items: center;
        padding: 4px 10px; /* Reduce la altura */
        border: 1px solid #ddd; /* Borde gris claro */
        border-radius: 6px;
        width: 100%;
        font-size: 14px;
        margin-bottom:0.5rem;
        height: 30px; /* Controla la altura */
    }

    .custom-input:focus {
        outline: none;
        box-shadow: none; /* Elimina borde azul en focus */
        border-color: #ccc; /* Borde ligeramente más oscuro al enfocar */
    }

    .hide{
        display: none;
    }

    </style>
    <div class="row mb-3">
        <div class="col-2 d-flex align-items-center">
            <button type="button" class="btn btn-sm action-btn btn-outline-secondary" wire:click="switchFiltros()">
                <i class="fa-solid fa-filter"></i> Filtros
            </button>
        </div>
        <div class="col-md-10 @if(!$filtrosActivos) hide @endif" id="Filtros">
            <div class="row">
                <div class="col-md-3">
                    <input type="text" class="custom-input" placeholder="Filtrar por nombre" wire:model.change="filtroNombre">
                </div>
                <div class="col-md-3">
                    <input type="text" class="custom-input" placeholder="Filtrar por categoria" wire:model.change="filtroCat">
                </div>
                <div class="col-md-3">
                    <select class="form-select form-select-sm" wire:model.change="filtroEstado">
                        <option value="-1">Todos</option>
                        <option value="0">Inactivos</option>
                        <option value="1">Activos</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col" style="cursor: pointer;" wire:click="ordenarBy('name')">
                    @if($ordenarPor=="name")
                    @if($ordenarDesc)
                        <a  class="text-decoration-none text-dark"> 
                            <i class="fa-solid fa-angle-down me-2"></i>
                        </a>
                    @else
                        <a class="text-decoration-none text-dark"> 
                            <i class="fa-solid fa-angle-up me-2"></i>
                        </a>
                    @endif
                    @endif
                    Nombre
                </th>
                <th scope="col">Categoría</th>
                <th scope="col">Estado</th>
                <th scope="col">Disponibles</th>
                <th scope="col" style="width: 6%"></th>
                <th scope="col" style="width: 15%"></th>
            </tr>
        </thead>
        <tbody class="align-center">
            @if(!$productos->isEmpty())
            @foreach ($productos as $producto)
            <tr>
                <th scope="row">{{$producto->id}}</th>
                <td>{{$producto->name}}</td>
                <td>
                    {{$producto->category_info->name??"SIN CATEGORÍA"}}
                </td>
                <td>
                    @if($producto->available)
                    <span style="color:green">Activo</span>
                    @else
                    <span style="color:red">Oculto</span>
                    @endif
                </td>
                <td>{{$producto->inventory->stock_available}}</td>
                <td>
                    @if($producto->inventory->stock_available>=$producto->inventory->stock_min && $producto->inventory->stock_available!=0)
                    <i class="fa-solid fa-circle-check" style="color:green"></i>
                    @elseif($producto->inventory->stock_available>0)
                    <i class="fa-solid fa-circle-exclamation" style="color:orange"></i>
                    @else
                    <i class="fa-solid fa-circle-xmark" style="color:red"></i>
                    @endif
                </td>
                <td>
                    <a type="button" class="btn btn-outline-secondary btn-sm" href="{{route("inventario.viewedit",$producto->id)}}">Ver detalles</a>
                </td>
            </tr>
            @endforeach

            @else
            <tr class="text-center">
                <td colspan="7">Sin productos registrados aún</td>
            </tr>
            @endif
        </tbody>
    </table>
    
    @if(auth()->check() && auth()->user()->rank >= 4)
    <center><a class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#createProduct">Crear nuevo producto</a></center>

    <!-- Modal -->
    
    <div class="modal fade" id="createProduct" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="createProductLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="createProductLabel">Creación de nuevo producto</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    Por favor ingresa la siguiente información para registrar un nuevo producto en el sistema. Los productos inician con el inventario en cero, es por ello que, si deseas añadir stock, podrás hacerlo en la página de cada producto.
                </p>

                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" placeholder="Ejemplo: Juguetes" wire:model="name">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">¿Tendrán id de Firmware?</label>
                        <select class="form-select" wire:model="firmware">
                            <option disabled value="-1">Seleccionar una opción</option>
                            <option value="0">No</option>
                            <option value="1">Si</option>
                        </select>
                    </div>
                    <div class="col-md-12 mb-3">
                        <labelclass="form-label">Descripción</label>
                        <textarea class="form-control" rows="3" placeholder="Aquí describe de forma corta qué es este producto, esta información es de uso personal para referenciación u otros." wire:model="description"></textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Categoría</label>
                        <select class="form-select" wire:model="category">
                            <option selected disabled value="-1">Seleccionar una categoría</option>
                            <option value="0">Ninguna</option>
                            @foreach ($categorias as $categoria)
                            <option value="{{$categoria->id}}">{{$categoria->name}}</option>
                            @endforeach
                            
                        </select>
                    </div>                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Referencia</label>
                        <input type="text" class="form-control" placeholder="Ejemplo: 001RG" wire:model="ref">
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:click="guardar()">Crear producto</button>
            </div>
            </div>
        </div>
    </div>
    @endif
</div>
