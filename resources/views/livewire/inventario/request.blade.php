<div>
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="card-title">Creación de pedidos</h4>
                    <p class="card-text">Para poder crear un pedido por favor completa la información de cada una de las secciones. Recuerda que un pedido es una solicitud que hace Robbocock a otra compañía, mientras que un a orden es una solicitud que se le hace a Robbocock.</p>
                </div>
                <div class="col-md-12 pt-4 mb-3">
                    <h5 class="card-title">Información de proveedor/contratista</h5>
                    <p class="card-text">Esta es la información respecto de quién se encarga del pedido.</p>
                </div>
                <div class="col-md-12">
                    
                    <div class="row">
                        <div class="col-12 col-xl-5">
                            <label class="form-label">Nombre del proveedor</label>
                            <input type="text" class="form-control" placeholder="Ejemplo: Coolsoft" wire:model="empresa">
                            <br>
                        </div>
                        <div class="col-6 col-xl-3">
                            <label class="form-label">Ciudad (Opcional)</label>
                            <input type="text" class="form-control" placeholder="Ejemplo: Medellín" wire:model="ciudad">
                            <br>
                        </div>
                        <div class="col-6 col-xl-4">
                            <label class="form-label">Dirección (Opcional)</label>
                            <input type="text" class="form-control" placeholder="Ejemplo: Calle 56 #80-30" wire:model="direccion">
                            <br>
                        </div>
                        <div class="col-12 col-xl-5">
                            <label class="form-label">Nombre del responsable (Opcional)</label>
                            <input type="text" class="form-control" placeholder="Ejemplo: Juan Carlos Gomez" wire:model="nombre">
                            <br>
                        </div>
                        <div class="col-6 col-xl-4">
                            <label for="telname" class="form-label">Contacto (Opcional)</label>
                            <input type="tel" class="form-control" id="telname" aria-describedby="telnameHelp" placeholder="Ejemplo: +573007885858" wire:model="telefono">
                            <br>
                        </div>
                        <div class="col-6 col-xl-3">
                            <label class="form-label">Fecha posible de entrega</label>
                            <input type="date" class="form-control" wire:model="fecha">
                            <br>
                        </div>
                        
                    </div>
                    
                </div>
                <div class="col-md-12 pt-3">
                    <h5 class="card-title">Listado de productos</h5>
                    <p class="card-text">Este es el listado de elementos que se solicitarán en este pedido.
                        Los productos internos corresponden a los inventariados, mientras que los externos son productos que hacen parte del pedido pero no del inventario.
                    </p>
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
                                    <td>
                                        @if($pd["internal"])
                                        {{$pd["name"]}}
                                        @else
                                        <input class="form-control form-control-sm " type="text" wire:model="listProducts.{{$index}}.name" >
                                        @endif
                                        
                                    </td>
                                    <td>
                                        <input type="number" min="1" class="form-control form-control-sm" wire:model="listProducts.{{$index}}.amount" >
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
                    <div class="d-flex justify-content-center align-items-center gap-3">
                        <a class="btn btn-outline-secondary btn-sm" wire:click="addExternal()">Añadir item externo</a>
                        @if(!$addingProduct)
                        <a class="btn btn-outline-secondary btn-sm" wire:click="startAdding()">Añadir item interno</a>
                        @endif   
                    </div>
                    @if($addingProduct)
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
                            <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="cancelSearch()">Finalizar búsqueda</button>
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
                    <p class="card-text">Aquí podrás añadir las observaciones que consideres importantes para este pedido.</p>
                    
                    <div class="mb-3">
                        <textarea class="form-control" rows="3" wire:model="observaciones"></textarea>
                    </div>

                </div>
                <div class="col-md-12">
                    <div class="text-center">
                        <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">Registrar pedido en sistema</a>
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