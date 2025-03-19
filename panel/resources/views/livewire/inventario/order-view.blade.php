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

        .progressbar li:not(.current) ~ li span {
            color: #D2665A; /* Restaura el color original en los pasos después del actual */
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
            background-color: #3f3f3f !important; /* Línea roja */
        }

        .progressbar.cancelled li .icon-circle {
            background-color: #3f3f3f !important; /* Círculos rojos */
        }

        .progressbar.cancelled i {
            color: white !important; /* Íconos blancos para contraste */
        }

        .progressbar.cancelled li span {
            color: #3f3f3f !important; /* Texto rojo para coherencia */
        }
    </style>
    <div class="card shadow-custom">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <h5 class="card-title">Información de la orden</h5>
                    <p class="card-text">Esta es la información que está almacenada para este producto.</p>
                </div>
                <div class="col-md-12 pt-3">
                    <ul class="progressbar @if($orden->status=="canceled") cancelled @endif">
                        <li @if($orden->status=="created") class="current" @endif>
                            <div class="icon-circle">
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <span>Creación</span>
                        </li>
                        <li @if($orden->status=="prepared") class="current" @endif>
                            <div class="icon-circle">
                                <i class="fa-solid fa-box"></i>
                            </div>
                            <span>Preparación</span>
                        </li>
                        <li @if($orden->status=="waiting") class="current" @endif>
                            <div class="icon-circle">
                                <i class="fa-solid fa-hourglass-half"></i>
                            </div>
                            <span>Esperando recogida</span>
                        </li>
                        <li @if($orden->status=="sended") class="current" @endif>
                            <div class="icon-circle">
                                <i class="fa-solid fa-truck"></i>
                            </div>
                            <span>Enviado</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>