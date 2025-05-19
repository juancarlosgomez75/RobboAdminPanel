<div>
    {{-- @if($stock>=$stockmin && $activo)
    <div class="alert alert-success" role="alert">
        El stock disponible de este producto se encuentra por encima del stock recomendable
    </div>
    @elseif($stock>0 && $activo)
    <div class="alert alert-warning" role="alert">
        El stock disponible de este producto está por debajo del stock recomendable
    </div>
    @elseif($stock==0 && $activo)
    <div class="alert alert-danger" role="alert">
        Este producto no tiene stock disponible
    </div>
    @endif --}}
    {{-- {{json_encode($listProducts)}} --}}
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="card-title">Creación de órdenes</h4>
                    <p class="card-text">Para poder crear una orden por favor completa la información de cada una de las secciones.</p>
                </div>
                <div class="col-md-12 pt-4">
                    <h5 class="card-title">Información de destinatario</h5>
                    <p class="card-text">Esta es la información respecto de hacia donde irá dirigido el paquete.</p>
                </div>
                <div class="col-md-12 pt-3">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-2 mb-3">
                                    <label class="form-label">¿Es un estudio?</label>
                                    <select class="form-control" wire:model.change="toStudy">
                                        <option value="-1" disabled selected>Seleccione</option>
                                        <option value="0">No</option>
                                        <option value="1">Sí</option>
                                    </select>
                                </div>

                                @if($toStudy=="1")
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Ingrese nombre a buscar</label>
                                    <input type="text" class="form-control" placeholder="Ejemplo: Coolcam" wire:model="study_search" required >
                                </div>
                                <div class="col-md-2 mb-3 pt-2 d-grid">
                                    <br>
                                    <button type="button" class="btn btn-outline-primary" wire:click="buscarEstudio()">Buscar</button>
                                </div>
                                @if($studyFind=="1")
                                <div class="col-md-4 mb-3">
                                    <label class="form-label">Estudio seleccionado</label>
                                    <input type="text" class="form-control" wire:model="study_name" disabled>
                                </div>
                                @endif

                                @endif
                            </div>
                        </div>

                        @if($activeBasic)
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Dirección</label>
                            <input type="text" class="form-control" placeholder="Ejemplo: Calle 98 #69-12" wire:model="address" @if($studyFind=="1") disabled @endif>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ciudad</label>
                            <input type="text" class="form-control" placeholder="Ejemplo: Medellín, Colombia" wire:model="city" @if($studyFind=="1") disabled @endif >
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nombre de quién recibe</label>
                            <input type="text" class="form-control" placeholder="Ejemplo: Juan Carlos" wire:model="receiver" @if($studyFind=="1") disabled @endif >
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Teléfono de contacto</label>
                            <input type="tel" class="form-control" placeholder="Ejemplo: +573002000000" wire:model="phone" @if($studyFind=="1") disabled @endif >
                        </div>
                        @endif
                    </div> 
                </div>
                <div class="col-md-12 pt-3 mb-2">
                    <h5 class="card-title">Tipo de orden</h5>
                    <p class="card-text">Indique aquí la naturaleza de esta orden</p>
                    
                    <select class="form-select" wire:model="tipoOrden">
                        <option disabled value="0">Seleccione una opción</option>
                        <option value="-1">Envío: Los items se descontarán del inventario</option>
                        <option value="1">Recogida: Los items se sumarán al inventario</option>
                    </select>
                </div>
                <div class="col-md-12 pt-3">
                    <h5 class="card-title">Listado de productos</h5>
                    <p class="card-text">Este es el listado de elementos que se deberán enviar en esta orden.</p>
                </div>
                <div class="col-md-12 pt-2">
                    <table class="table text-center" >
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nombre</th>
                                <th scope="col" style="width:10%">Cantidad</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!empty($listProducts))
                            @foreach ($listProducts as $index=>$pd)
                                <tr>
                                    <td>{{$index+1}}</td>
                                    <td>{{$pd["name"]}}</td>
                                    <td>
                                        <input type="number" min="1" class="form-control" wire:model="listProducts.{{$index}}.amount" >
                                    </td>
                                    <td>
                                        <a class="btn btn-outline-danger btn-sm" wire:click="removeProduct({{$index}})">Eliminar</a>
                                    </td>
                                </tr>
                            @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center">Aún no se han añadido productos</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12">
                    @if(!$addingProduct)
                    <div class="text-center">
                        <a class="btn btn-outline-secondary btn-sm" wire:click="startAdding()">Añadir item</a>
                    </div>    
                    @else
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label class="form-label">Buscar producto por nombre</label>
                            <input type="text" class="form-control" placeholder="Ejemplo: Neutro" wire:model="product_name" >
                        </div>
                        <div class="col-md-2 mb-3 pt-2 d-grid text-center">
                            <br>
                            <button type="button" class="btn btn-outline-primary btn-sm" wire:click="searchProduct()">Buscar</button>
                        </div>
                        <div class="col-md-2 mb-3 pt-2 d-grid text-center">
                            <br>
                            <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="cancelSearch()">Finalizar</button>
                        </div>
                        @if($finded)
                        <div class="col-md-12 mb-3">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Stock disponible</th>
                                        <th scope="col" style="width:20%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(!$searchResults->isEmpty())
                                    @foreach ($searchResults as $index=>$result)
                                    <tr>
                                        <th scope="row">{{$index+1}}</th>
                                        <td>{{$result->name}}</td>
                                        <td>{{$result->inventory->stock_available}}</td>
                                        <td class="d-grid">
                                            <a class="btn btn-outline-primary btn-sm" wire:click="addProduct({{$index}})">Añadir</a>
                                        </td>
                                    </tr>
                                    @endforeach

                                    @else
                                    <tr class="text-center">
                                        <td colspan="4">No hubo coincidencia de productos con este nombre</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        @endif
                    </div>
                    @endif
                </div>
                <div class="col-md-12 pt-3">
                    <h5 class="card-title">Observaciones</h5>
                    <p class="card-text">Aquí podrás añadir las observaciones que consideres importantes para esta orden.</p>
                    
                    <div class="mb-3">
                        <textarea class="form-control" rows="3" wire:model="details"></textarea>
                    </div>

                </div>
                <div class="col-md-12">
                    <div class="text-center">
                        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Registrar pedido en sistema</a>
                    </div>   
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirmación de creación de pedido</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>
                        Al presionar en confirmar, aceptas que toda la información aquí descrita está bien. Por seguridad, la información no podrá ser editada manualmente.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Regresar</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:click="crear()" >Crear pedido</button>
                </div>
            </div>
            </div>
        </div>


    </div>
</div>