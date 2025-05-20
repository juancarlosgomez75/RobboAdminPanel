<div>
    <style>
        .container {
            width: 100%;
        }
    
        .progressbar {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10vw; /* Espacio entre los elementos */
            padding: 20px 0; /* Espaciado superior e inferior */
            position: relative;
        }
    
        /* Estilo de los elementos de la barra de progreso */
        .progressbar li {
            list-style: none;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 0.5em;
            position: relative;
        }
    
        /* Círculo alrededor del ícono */
        .icon-circle {
            width: 50px;
            height: 50px;
            background-color: #007bff; /* Color de fondo */
            border-radius: 50%; /* Hace el div completamente circular */
            display: flex;
            justify-content: center;
            align-items: center;
            position: relative;
            z-index: 1; /* Asegura que esté encima de la línea */
        }
    
        /* Ícono dentro del círculo */
        .progressbar i {
            font-size: clamp(16px, 1.7vw, 18px);
            color: white; /* Color del ícono */
        }
    
        /* Línea de conexión entre los círculos */
        .progressbar li::after {
            content: "";
            position: absolute;
            top: 25px;
            width: 15.2vw; /* Ajusta el tamaño de la línea */
            height: 5px; /* Grosor de la línea */
            background-color: #c0c0c0; /* Color de la línea */
            left: 50%;
            z-index: 0; /* Detrás de los círculos */
        }
    
        /* Elimina la línea del último elemento */
        .progressbar li:last-child::after {
            content: none;
        }
    
        /* Estilos para los pasos completados */
        .progressbar li.current ~ li .icon-circle {
            background-color: #c0c0c0; /* Pasos después del actual en gris */
        }
    
        .progressbar li.current .icon-circle {
            background-color: #D2665A; /* Paso actual en amarillo */
        }

        .progressbar li.current span {
            color: #D2665A !important; /* Paso actual en amarillo */
        }

        .progressbar li:not(.current) ~ li span {
            color: #d8a6a1; /* Restaura el color original en los pasos después del actual */
        }

        .progressbar li:not(.current) .icon-circle {
            background-color: #d8a6a1;
        }

        .progressbar li:not(.current)::after {
            background-color: #d8a6a1; /* Línea verde hasta el paso actual */
        }


        .progressbar li:not(.current) span {
            color: #d8a6a1; /* Cambia el color solo en los pasos antes del actual */
        }
    
        /* Línea de conexión verde hasta el paso actual */
        .progressbar li.current ~ li::after {
            background-color: #c0c0c0; /* Mantiene la línea gris después del paso actual */
        }

        .progressbar li.current ~ li span {
            color: #c0c0c0; /* Asegura que los pasos después del actual no se vean afectados */
        }


        /* Si la clase 'cancelled' está presente, todo se vuelve rojo */
        .progressbar.cancelled li::after {
            background-color: #c0c0c0 !important; /* Línea roja */
        }

        .progressbar.cancelled li .icon-circle {
            background-color: #c0c0c0 !important; /* Círculos rojos */
        }

        .progressbar.cancelled i {
            color: white !important; /* Íconos blancos para contraste */
        }

        .progressbar.cancelled li span {
            color: #c0c0c0 !important; /* Texto rojo para coherencia */
        }

        /* Si la clase 'cancelled' está presente, todo se vuelve rojo */
        .progressbar.completed li::after {
            background-color: #d8a6a1 !important; /* Línea roja */
        }

        .progressbar.completed li .icon-circle {
            background-color: #d8a6a1 !important; /* Círculos rojos */
        }

        .progressbar.completed i {
            color: white !important; /* Íconos blancos para contraste */
        }

        .progressbar.completed li span {
            color: #d8a6a1 !important; /* Texto rojo para coherencia */
        }
    </style>
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="card-title">Información de el pedido</h4>
                    <p class="card-text">Esta es la información que está almacenada para este producto.</p>
                </div>
                <div class="col-md-12 pt-4">
                    <ul class="progressbar @if($pedido->status=="canceled") cancelled @endif">
                        <li>
                            <div class="icon-circle">
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <span>Creado</span>
                        </li>
                        <li @if($pedido->status=="created" || $pedido->status=="partial delivery") class="current" @endif>
                            <div class="icon-circle">
                                <i class="fa-solid fa-box"></i>
                            </div>
                            <span>
                                @if($pedido->status=="created")
                                Esperando entrega
                                @elseif($pedido->status=="partial delivery")
                                Entrega parcial
                                @else
                                Preparado
                                @endif
                            </span>
                        </li>
                        <li @if($pedido->status=="delivered") class="current" @endif>
                            <div class="icon-circle">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <span>
                                @if($pedido->status=="delivered")
                                Completado
                                @else
                                Pendiente
                                @endif
                            </span>
                        </li>
                    </ul>
                </div>
                @if($pedido->status=="canceled")
                <div class="col-md-12 pb-1 text-center">
                    <h5 style="color:red">Pedido cancelado</h5>
                </div>
                @endif
                <div class="col-md-12 pt-2">
                    <h5 class="card-title">Información de creación</h5>
                    <p class="card-text">Esta es la información relacionada con la creación del pedido.</p>
                </div>
                <div class="col-md-12 pt-2">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="row">Nombre de empresa</th>
                                <td>{{$pedido->enterprise}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Ciudad</th>
                                <td>{{($pedido->city!="")?$pedido->city:"No registra"}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Dirección</th>
                                <td>{{($pedido->address!="")?$pedido->address:"No registra"}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Encargado</th>
                                <td>{{($pedido->name!="")?$pedido->name:"No registra"}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Teléfono</th>
                                <td>{{($pedido->phone!="")?$pedido->phone:"No registra"}}</td>
                            </tr>

                            <tr>
                                <th scope="row">Fecha de creación</th>
                                <td>{{$pedido->created_at}}</td>
                            </tr>

                            <tr>
                                <th scope="row">Fecha tentativa de entrega</th>
                                <td>{{$pedido->tentative_delivery_date}}</td>
                            </tr>

                            <tr>
                                <th scope="row">Creador</th>
                                <td>{{$pedido->creator_info->name." (".$pedido->creator_info->id." - ".$pedido->creator_info->username.")"}}</td>
                            </tr>
                            <tr>
                                <th scope="row">Comentarios de creación</th>
                                <td>{{$pedido->creation_notes}}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                @if($pedido->status=="canceled")
                <div class="col-md-12 pt-2">
                    <h5 class="card-title">Información de cancelación</h5>
                    <p class="card-text">Esta es la información relacionada con la cancelación de la orden</p>
                </div>
                <div class="col-md-12 pt-2">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="row">Fecha de cancelación</th>
                                <td>{{$pedido->cancel_date??"No reporta"}}</td>
                            </tr>

                            <tr>
                                <th scope="row">¿Quién canceló la orden?</th>
                                <td>{{(!is_null($pedido->canceled_by))? $pedido->canceler_info->name." (".$pedido->canceler_info->id." - ".$pedido->canceler_info->username.")":"No reporta"}}</td>
                            </tr>

                            <tr>
                                <th scope="row">Razón de cancelación</th>
                                <td>{{$pedido->cancellation_reason??"No reporta"}}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                @endif
                                @if(!is_null($deliveryList))
                <div class="col-md-12 pt-2">
                    <h5 class="card-title">Información de entregas</h5>
                    <p class="card-text">Este es el listado de comentarios de las entregas reportadas.</p>
                    @foreach($deliveryList as $fecha=>$contenido)
                    <p>
                        <b>{{$fecha}}</b> - 
                        @if($contenido["inventoried"])
                        <span class="text-success">{{"Reportada en inventario el: ".$contenido["inventoried_date"]??"N/F"." por #".$contenido["inventoried_by"]??"N/R"}}</span>
                        @else 
                        <span class="text-primary">Sin reportar en inventario</span>
                        @endif
                        <br>
                        {{$contenido["details"]??"Sin comentarios"}}
                    </p>
                    @endforeach
                </div>
                @endif

                <div class="col-md-12 pt-2">
                    <h5 class="card-title">Información de productos</h5>
                    <p class="card-text">Estos son los productos que deben ser entrgados</p>
                </div>
                <div class="col-md-12 pt-2">
                    <table class="table text-center" >
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">¿Registrado?</th>
                                @if($entregaActive)
                                <th scope="col">Reporte de entrega</th>
                                @endif
                                @if(!is_null($deliveryList))
                                @foreach($deliveryList as $fecha=>$contenido)
                                <th scope="col">
                                    {{ \Carbon\Carbon::parse($fecha)->toDateString() }}
                                    @if($contenido["inventoried"])
                                    <i class="fa-solid fa-check ms-2 text-success"></i>
                                    @else
                                    <i class="fa-solid fa-hourglass-start ms-2 text-primary"></i>
                                    @endif
                                </th>
                                @endforeach
                                @endif
                                <th scope="col">Pendiente</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (json_decode($pedido->creation_list) as $index=>$pd)
                                <tr>
                                    <td>
                                        {{$index+1}}
                                    </td>
                                    <td>
                                        {{$pd->name}}
                                    </td>
                                    <td>
                                        {{$pd->amount}}
                                    </td>
                                    <td>
                                        @if($pd->internal)
                                        <a href="{{route("inventario.viewedit",$pd->id)}}"></a>
                                        @else 
                                        No
                                        @endif
                                    </td>
                                    @if($entregaActive)
                                    <td>
                                        <input class="form-control form-control-sm" type="number" min="0" wire:model="entregando.{{$index}}">
                                    </td>
                                    @endif
                                    @if(!is_null($deliveryList))
                                    @foreach($deliveryList as $fecha=>$contenido)
                                    <td scope="col">{{$contenido["products"][$index]}}</td>
                                    @endforeach
                                    @endif
                                    <td>
                                        {{$pendientes[$index]}}
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
                <div class="col-12 d-flex justify-content-center">
                    <div class="d-flex gap-3 justify-content-center w-100" style="max-width: 36rem;">
                        @if($pedido->status!="delivered")
                            @if(!$entregaActive)
                            <button type="button" class="btn btn-outline-secondary" wire:click="iniciarEntrega()">
                                Reportar entrega
                            </button>
                            @else
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                Completar entrega
                            </button>
                            @endif
                            
                            <button type="button" class="btn btn-outline-danger" wire:click="cancelarPedido()">
                                Cancelar pedido
                            </button>
                        @endif
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
                <h1 class="modal-title fs-5" id="staticBackdropLabel">Confirmación de reporte de entrega</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>
                    Al presionar en confirmar, aceptas que toda la información aquí descrita está bien. Por seguridad, la información no podrá ser editada manualmente.
                </p>
                <div class="mb-3">
                    <label class="form-label">Observaciones de entrega</label>
                    <textarea class="form-control" rows="3" wire:model="observaciones"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Regresar</button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" wire:click="completarEntrega()" >Crear pedido</button>
            </div>
        </div>
        </div>
    </div>
    <br>
</div>