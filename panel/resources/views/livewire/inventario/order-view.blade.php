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
            width: 16vw; /* Ajusta el tamaño de la línea */
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
                    <h4 class="card-title">Información de la orden</h4>
                    <p class="card-text">Esta es la información que está almacenada para este producto.</p>
                </div>
                <div class="col-md-12 pt-4">
                    <ul class="progressbar @if($orden->status=="canceled") cancelled @elseif($orden->status=="sended") completed @endif">
                        <li>
                            <div class="icon-circle">
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <span>Creada</span>
                        </li>
                        <li @if($orden->status=="created") class="current" @endif>
                            <div class="icon-circle">
                                <i class="fa-solid fa-box"></i>
                            </div>
                            <span>
                                @if($orden->status=="created")
                                Preparando
                                @else
                                Preparado
                                @endif
                            </span>
                        </li>
                        <li @if($orden->status=="prepared") class="current" @endif>
                            <div class="icon-circle">
                                <i class="fa-solid fa-hourglass-half"></i>
                            </div>
                            <span>
                                @if($orden->status=="prepared")
                                Esperando recogida
                                @elseif($orden->status!="created")
                                Recogida confirmada
                                @else
                                Recogida de orden
                                @endif
                            </span>
                        </li>
                        <li @if($orden->status=="waiting") class="current" @endif>
                            <div class="icon-circle">
                                <i class="fa-solid fa-truck"></i>
                            </div>
                            <span>
                                @if($orden->status=="waiting")
                                Enviando
                                @elseif($orden->status=="sended")
                                Enviado
                                @else
                                Envío
                                @endif
                            </span>
                        </li>
                    </ul>
                </div>
                <div class="col-md-12 pt-2">
                    <h5 class="card-title">Información de creación</h5>
                    <p class="card-text">Esta es la información relacionada con la creación de la orden.</p>
                </div>
                <div class="col-md-12 pt-2">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th scope="row">Nombre de destinatario</th>
                                <td>{{$orden->name}}</td>
                            </tr>

                            <tr>
                                <th scope="row">Teléfono</th>
                                <td>{{$orden->phone}}</td>
                            </tr>

                            <tr>
                                <th scope="row">Ciudad</th>
                                <td>{{$orden->city}}</td>
                            </tr>

                            <tr>
                                <th scope="row">Dirección</th>
                                <td>{{$orden->address}}</td>
                            </tr>

                            <tr>
                                <th scope="row">¿Asociado a estudio?</th>
                                <td>
                                    @if($orden->study_id)
                                    <a href="{{route("estudio.ver",$orden->study_id)}}">Ver estudio</a>
                                    @else
                                    No
                                    @endif
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">Fecha de creación</th>
                                <td>{{$orden->created_at}}</td>
                            </tr>

                            <tr>
                                <th scope="row">Credor</th>
                                <td>{{$orden->creator_info->name." (".$orden->creator_info->id." - ".$orden->creator_info->username.")"}}</td>
                            </tr>

                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 pt-2">
                    <h5 class="card-title">Información de productos a enviar</h5>
                    <p class="card-text">Esta estos son los productos que se deben enviar</p>
                </div>
                <div class="col-md-12 pt-2">
                    <table class="table text-center" >
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach (json_decode($orden->creation_list) as $index=>$pd)
                                <tr>
                                    <td>{{$index+1}}</td>
                                    <td>{{$pd->name}}</td>
                                    <td>{{$pd->amount}}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>