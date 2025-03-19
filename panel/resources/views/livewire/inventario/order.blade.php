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
                <div class="col-md-8 pt-3">
                    <h5 class="card-title">Historial de movimientos</h5>
                    <p class="card-text">Estos son los últimos movimientos que se han efectuado para este producto.</p>
                </div>
                <div class="col-md-4 pt-4 text-end">
                    <a class="btn btn-outline-secondary btn-sm">
                        Crear un movimiento
                    </a>
                </div>
                <div class="col-md-12 pt-2">
                    <table class="table text-center" >
                        <thead>
                            <tr>
                                <th scope="col">Fecha</th>
                                <th scope="col">Razón</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Stock antes</th>
                                <th scope="col">Stock despues</th>
                                <th scope="col">Autor</th>
                                <th scope="col">Transacción</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirmación de modificación</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>
                        Al presionar en confirmar edición, confirma que la información aquí contenida es correcta.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Regresar</button>
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:click="guardarEdicion" >Confirmar edición</button>
                </div>
            </div>
            </div>
        </div>


    </div>
</div>