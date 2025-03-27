<div>
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <h5 class="card-title">Empresas de mensajería</h5>
                    <p class="card-text">Estas son las empresas de mensajería disponibles en este momento en tu sistema:</p>
                </div>
                <div class="col-md-12">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Página</th>
                                <th scope="col">Fecha de creación</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(!$couriers->isEmpty())
                            @foreach ($couriers as $courier)
                            <tr>
                                <th>{{$courier->id}}</th>
                                <td>{{$courier->name}}</td>
                                <td>{{$courier->page}}</td>
                                <td>{{$courier->created_at}}</td>
                                <td>{{$courier->page}}</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td colspan="5" class="text-center">No hay empresas de mensajería registradas en este momento.</td>
                            </tr>
                            @endif
                        </tbody>
                        </table>
                </div>
                <div class="col-md-12 text-center">
                    <a class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#createCourier">
                        Registrar empresa nueva
                    </a>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="createCourier" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5">Registro de mensajería</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>
                            Por favor completa la información para registrar a la empresa de envíos
                        </p>
                        <div class="mb-3">
                            <label class="form-label">Nombre de la empresa</label>
                            <input type="text" class="form-control" placeholder="Ejemplo: Coordinadora" wire:model="name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Página web</label>
                            <input type="text" class="form-control" placeholder="Ejemplo: https://coordinadora.com/" wire:model="page">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Regresar</button>
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:click="guardar" >Confirmar registro</button>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>