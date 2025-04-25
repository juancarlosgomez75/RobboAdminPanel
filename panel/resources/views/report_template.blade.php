<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('img/bb_fondo.png') }}">
    <title>Reporte en PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        /* table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        } */
        img {
            width: 170px;
            height: auto;
        }
        .page-break {
            page-break-before: always; /* Esto crea el salto de página */
        }

    </style>
</head>
<body>

    <table>
        <tr>
            <td style="width: 50%">
                <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('img/Logo.jpg'))) }}" alt="Logo">
            </td>
            <td style="text-align: center">
                <p>
                    {{$fechaActual}}
                    <br>
                    <b style="color:#c84b46; font-size: 25px; padding-top: 50px;">
                        REPORTE GENERAL
                    </b>
                    <br>
                    <b>Periodo: </b> {{ucfirst($fechaInicio)}} - {{ucfirst($fechaFin)}}
                </p>
            </td>
        </tr>
    </table>
    <br><br>
    <table>
        <tr>
            <td style="width: 50%">
                <p>
                    <b style="color:#c84b46; font-size: 16px;">Remitente:</b><br>
                    <b>COOLSOFT TECHNOLOGY SAS</b><br>
                    <span style="font-size: 12px;">
                        NIT. 901389093<br>
                        Calle 103 # 45A - 14<br>
                        BOGOTÁ, COLOMBIA<br>
                        3108197185
                    </span>
                </p>
            </td>
            <td>
                <td style="width: 50%">
                    <p>
                        <b style="color:#c84b46; font-size: 16px;">Destinatario:</b><br>
                        <b>{{$data["RazonSocial"]}}</b><br>
                        <span style="font-size: 12px;">
                            NIT. {{$data["Nit"]}}<br>
                            {{$data["Address"]}}<br>
                            {{$data["City"]}}<br>
                            {{$data["Phone"]}}
                        </span>
                    </p>
            </td>
        </tr>
    </table>

    <br><br>

    <style>
        .report-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 12px
        }
    
        .report-table th {
        background-color: #c84b46;
        color: white;
        padding: 3px;
        text-align: center;
        vertical-align: middle;
        font-size: 13px
        }
    
        .report-table td {
        text-align: center;
        vertical-align: middle;
        padding: 3px;
        border: 1px solid #d3d3d3; /* gris claro */
        }
    </style>

    <b>Información de acciones por máquinas:</b><br>
    <table class="report-table">
        <thead>
            <tr>
                <th scope="col" colspan="2">Acción</th>
                @foreach ($data["ResultsReport"]["Maquinas"] as $Maquina=>$info)
                <th scope="col">#{{$Maquina}}</th>
                @endforeach
                <th scope="col">Total</th>

            </tr>
        </thead>
        <tbody>

            {{-- MOV --}}
            <tr>
                <td rowspan="2">MOV</td>
                <td>Cantidad</td>
                @foreach($data["ResultsReport"]["Maquinas"] as $Maquina=>$info)
                <td>{{ $info["Acciones"]["MOV"]["Cantidad"] ?? 0 }}</td>
                @endforeach
                <td>{{ $data["ResultsReport"]["Acciones"]["MOV"]["Cantidad"] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Tiempo (Minu)</td>
                @foreach($data["ResultsReport"]["Maquinas"] as $Maquina=>$info)
                <td>{{ number_format(($info["Acciones"]["MOV"]["Tiempo"] ?? 0) / 60, 2) }}</td>
                @endforeach
                <td>{{ number_format(($data["ResultsReport"]["Acciones"]["MOV"]["Tiempo"] ?? 0) / 60, 2) }}</td>
            </tr>

            {{-- CONTROL --}}
            <tr>
                <td rowspan="2">CONTROL</td>
                <td>Cantidad</td>
                @foreach($data["ResultsReport"]["Maquinas"] as $Maquina=>$info)
                <td>{{ $info["Acciones"]["CONTROL"]["Cantidad"] ?? 0 }}</td>
                @endforeach
                <td>{{ $data["ResultsReport"]["Acciones"]["CONTROL"]["Cantidad"] ?? 0 }}</td>
            </tr>
            <tr>
                <td>Tiempo (Minu)</td>
                @foreach($data["ResultsReport"]["Maquinas"] as $Maquina=>$info)
                <td>{{ number_format(($info["Acciones"]["CONTROL"]["Tiempo"] ?? 0) / 60, 2) }}</td>
                @endforeach
                <td>{{ number_format(($data["ResultsReport"]["Acciones"]["CONTROL"]["Tiempo"] ?? 0) / 60, 2) }}</td>
            </tr>

            {{-- CUM --}}
            <tr>
                <td>CUM</td>
                <td>Cantidad</td>
                @foreach($data["ResultsReport"]["Maquinas"] as $Maquina=>$info)
                <td>{{ $info["Acciones"]["CUM"]["Cantidad"] ?? 0 }}</td>
                @endforeach
                <td>{{ $data["ResultsReport"]["Acciones"]["CUM"]["Cantidad"] ?? 0 }}</td>
            </tr>

            {{-- SCUM --}}
            <tr>
                <td>SCUM</td>
                <td>Cantidad</td>
                @foreach($data["ResultsReport"]["Maquinas"] as $Maquina=>$info)
                <td>{{ $info["Acciones"]["SCUM"]["Cantidad"] ?? 0 }}</td>
                @endforeach
                <td>{{ $data["ResultsReport"]["Acciones"]["SCUM"]["Cantidad"] ?? 0 }}</td>
            </tr>

            {{-- XCUM --}}
            <tr>
                <td>XCUM</td>
                <td>Cantidad</td>
                @foreach($data["ResultsReport"]["Maquinas"] as $Maquina=>$info)
                <td>{{ $info["Acciones"]["XCUM"]["Cantidad"] ?? 0 }}</td>
                @endforeach
                <td>{{ $data["ResultsReport"]["Acciones"]["XCUM"]["Cantidad"] ?? 0 }}</td>
            </tr>

        </tbody>
    </table>

    <br><br>

    <b>Información de tokens por páginas y modelos:</b><br>
    <table class="report-table">
        <thead>
            <tr>
                <th scope="col">Modelo</th>
                @foreach ($data["ResultsReport"]["Paginas"] as $pagina=>$info)
                <th scope="col">{{ $pagina == "SIMULADOR" ? "MANUAL" : $pagina }}</th>
                @endforeach
                <th scope="col">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data["ResultsReport"]["Modelos"] as $modelo=>$info)
            <tr>
                <td>{{$modelo}}</td>
                @foreach ($data["ResultsReport"]["Paginas"] as $pagina=>$_)
                <td>{{$info["Paginas"][$pagina]["Tokens"]??0}}</td>
                @endforeach
                <td>{{$info["Tokens"]??0}}</td>

            </tr>
            @endforeach
            <tr>
                <td><b>Total</b></td>
                @foreach ($data["ResultsReport"]["Paginas"] as $pagina=>$info)
                <td>{{$info["Tokens"]??0}}</td>
                @endforeach
                <td><b>{{$data["ResultsReport"]["Tokens"]}}</b></td>
            </tr>
        </tbody>
    </table>

    <br><br>

    <b>Información de acciones por modelos:</b><br>
    <table class="report-table">
        <thead>
            <tr>
                <th scope="col">Modelo</th>
                <th scope="col">MOV (Minu)</th>
                <th scope="col">CONTROL (Minu)</th>
                <th scope="col">CUM</th>
                <th scope="col">SCUM</th>
                <th scope="col">XCUM</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data["ResultsReport"]["Modelos"] as $modelo=>$info)
            <tr>
                <td>{{$modelo}}</td>
                <td>{{ number_format(($info["Acciones"]["MOV"]["Tiempo"] ?? 0) / 60, 2) }}</td>
                <td>{{ number_format(($info["Acciones"]["CONTROL"]["Tiempo"] ?? 0) / 60, 2) }}</td>
                <td>{{ $info["Acciones"]["CUM"]["Cantidad"] ?? 0 }}</td>
                <td>{{ $info["Acciones"]["SCUM"]["Cantidad"] ?? 0 }}</td>
                <td>{{ $info["Acciones"]["XCUM"]["Cantidad"] ?? 0 }}</td>
            </tr>
            @endforeach
            <tr>
                <td><b>Total</b></td>
                <td>{{ number_format(($data["ResultsReport"]["Acciones"]["MOV"]["Tiempo"] ?? 0) / 60, 2) }}</td>
                <td>{{ number_format(($data["ResultsReport"]["Acciones"]["CONTROL"]["Tiempo"] ?? 0) / 60, 2) }}</td>
                <td>{{ $data["ResultsReport"]["Acciones"]["CUM"]["Cantidad"] ?? 0 }}</td>
                <td>{{ $data["ResultsReport"]["Acciones"]["SCUM"]["Cantidad"] ?? 0 }}</td>
                <td>{{ $data["ResultsReport"]["Acciones"]["XCUM"]["Cantidad"] ?? 0 }}</td>
            </tr>
        </tbody>
    </table>
    
    @if($data["Renta"]=="Compartida")

    <div class="page-break"></div>

    <table>
        <tr>
            <td style="width: 50%">
                <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('img/Logo.jpg'))) }}" alt="Logo">
            </td>
            <td style="text-align: center">
                <p>
                    {{$fechaActual}}
                    <br>
                    <b style="color:#c84b46; font-size: 25px; padding-top: 50px;">
                        REPORTE DE USO
                    </b>
                    <br>
                    <b>Periodo: </b> {{ucfirst($fechaInicio)}} - {{ucfirst($fechaFin)}}
                </p>
            </td>
        </tr>
    </table>
    <br><br>
    <table>
        <tr>
            <td style="width: 50%">
                <p>
                    <b style="color:#c84b46; font-size: 16px;">Remitente:</b><br>
                    <b>COOLSOFT TECHNOLOGY SAS</b><br>
                    <span style="font-size: 12px;">
                        NIT. 901389093<br>
                        Calle 103 # 45A - 14<br>
                        BOGOTÁ, COLOMBIA<br>
                        3108197185
                    </span>
                </p>
            </td>
            <td>
                <td style="width: 50%">
                    <p>
                        <b style="color:#c84b46; font-size: 16px;">Destinatario:</b><br>
                        <b>{{$data["RazonSocial"]}}</b><br>
                        <span style="font-size: 12px;">
                            NIT. {{$data["Nit"]}}<br>
                            {{$data["Address"]}}<br>
                            {{$data["City"]}}<br>
                            {{$data["Phone"]}}
                        </span>
                    </p>
            </td>
        </tr>
    </table>

    <br><br>
    <b>Información de cobros por acciones:</b><br>
    <table class="report-table">
        <thead>
            <tr>
                <th scope="col">Acción</th>
                <th scope="col">Cantidad</th>
                <th scope="col">Valor unidad</th>
                <th scope="col">Total</th>

            </tr>
        </thead>
        <tbody>

            {{-- MOV --}}
            <tr>
                <td>MOV (minutos)</td>
                <td>{{ number_format(($data["ResultsReport"]["Acciones"]["MOV"]["Tiempo"] ?? 0) / 60, 2) }}</td>
                <td>{{'$' . number_format($data["Montos"]["MOV"], 2)}}</td>
                <td>{{'$' . number_format($data["CobrosTotales"]["MOV"], 2)}}</td>
                
                
            </tr>

            {{-- CONTROL --}}
            <tr>
                <td>CONTROL (minutos)</td>
                <td>{{ number_format(($data["ResultsReport"]["Acciones"]["CONTROL"]["Tiempo"] ?? 0) / 60, 2) }}</td>
                <td>{{'$' . number_format($data["Montos"]["CONTROL"], 2)}}</td>
                <td>{{'$' . number_format($data["CobrosTotales"]["CONTROL"], 2)}}</td>
            </tr>

            {{-- CUM --}}
            <tr>
                <td>CUM</td>
                <td>{{ $data["ResultsReport"]["Acciones"]["CUM"]["Cantidad"] ?? 0 }}</td>
                <td>{{'$' . number_format($data["Montos"]["CUM"], 2)}}</td>
                <td>{{'$' . number_format($data["CobrosTotales"]["CUM"], 2)}}</td>
            </tr>

            {{-- SCUM --}}
            <tr>
                <td>SCUM</td>
                <td>{{ $data["ResultsReport"]["Acciones"]["SCUM"]["Cantidad"] ?? 0 }}</td>
                <td>{{'$' . number_format($data["Montos"]["SCUM"], 2)}}</td>
                <td>{{'$' . number_format($data["CobrosTotales"]["SCUM"], 2)}}</td>
            </tr>

            {{-- XCUM --}}
            <tr>
                <td>XCUM</td>
                <td>{{ $data["ResultsReport"]["Acciones"]["XCUM"]["Cantidad"] ?? 0 }}</td>
                <td>{{'$' . number_format($data["Montos"]["XCUM"], 2)}}</td>
                <td>{{'$' . number_format($data["CobrosTotales"]["XCUM"], 2)}}</td>
            </tr>
            <tr>
                <td colspan="2" style="border:0"></td>
                <td>
                    <b>Total: </b>
                </td>
                <td>
                    <b>{{'$' . number_format($data["CobrosTotales"]["Total"], 2)}}</b>
                </td>
            </tr>

        </tbody>
    </table>

    <br><br>
    <b>Información de cobros por acciones por modelos:</b><br>
    <table class="report-table">
        <thead>
            <tr>
                <th scope="col">Modelo</th>
                <th scope="col">MOV</th>
                <th scope="col">CONTROL</th>
                <th scope="col">CUM</th>
                <th scope="col">SCUM</th>
                <th scope="col">XCUM</th>
                <th scope="col">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data["CobrosModelos"] as $modelo=>$info)
            <tr>
                <td>{{$modelo}}</td>
                <td>{{'$' . number_format($info["MOV"], 2)}}</td>
                <td>{{'$' . number_format($info["CONTROL"], 2)}}</td>
                <td>{{'$' . number_format($info["CUM"], 2)}}</td>
                <td>{{'$' . number_format($info["SCUM"], 2)}}</td>
                <td>{{'$' . number_format($info["XCUM"], 2)}}</td>
                <td>{{'$' . number_format($info["Total"], 2)}}</td>
            </tr>
            @endforeach
            {{--MOV--}}
            <tr>
                <td><b>Total</b></td>
                <td>{{'$' . number_format($data["CobrosTotales"]["MOV"], 2)}}</td>
                <td>{{'$' . number_format($data["CobrosTotales"]["CONTROL"], 2)}}</td>
                <td>{{'$' . number_format($data["CobrosTotales"]["CUM"], 2)}}</td>
                <td>{{'$' . number_format($data["CobrosTotales"]["SCUM"], 2)}}</td>
                <td>{{'$' . number_format($data["CobrosTotales"]["XCUM"], 2)}}</td>
                <td><b>{{'$' . number_format($data["CobrosTotales"]["Total"], 2)}}</b></td>
            </tr>
        </tbody>

    </table>


    @endif

    <div class="page-break"></div>

    <b>Informe detallado de acciones:</b><br>
    <table class="report-table">
        <thead>
            <tr>
                <th scope="col">Modelo</th>
                <th scope="col">Accion</th>
                <th scope="col">Valor contratado</th>
                <th scope="col">Tokens</th>
                <th scope="col">Tiper</th>
                <th scope="col">Fecha Inicio</th>
                <th scope="col">Fecha Fin</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data["DetailedReport"] as $log)
            <tr>
                <td>{{$log["ModelData"]["ModelUserName"]}}</td>
                <td>{{$log["Action"]}}</td>
                <td>{{$log["ContratedValue"]}}</td>
                <td>{{$log["Tokens"]}}</td>
                <td>{{$log["Typer"]}}</td>
                <td>{{ \Carbon\Carbon::createFromTimestamp($log["StartDate"] / 1000)->toDateTimeString() }}</td>
                <td>{{ \Carbon\Carbon::createFromTimestamp($log["EndDate"] / 1000)->toDateTimeString() }}</td>
                
                
                {{-- <td>{{ number_format(($info["Acciones"]["MOV"]["Tiempo"] ?? 0) / 60, 2) }}</td>
                <td>{{ number_format(($info["Acciones"]["CONTROL"]["Tiempo"] ?? 0) / 60, 2) }}</td>
                <td>{{ $info["Acciones"]["CUM"]["Cantidad"] ?? 0 }}</td>
                <td>{{ $info["Acciones"]["SCUM"]["Cantidad"] ?? 0 }}</td>
                <td>{{ $info["Acciones"]["XCUM"]["Cantidad"] ?? 0 }}</td> --}}
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    <b style="color:#c84b46; font-size: 16px;">Términos y condiciones:</b><br>
    <p style="font-size: 12px; text-align: justify;">
        A esta remisión de servicios aplican las normas relativas a la letra de cambio (artículo 5 Ley 1231 de 2008). Con esta el Comprador declara haber recibido real y materialmente las mercancías o prestación de servicios descritos en este título - Valor. Número Autorización 18764017968771 aprobado en 20210913 prefijo CT desde el número 1 al 50 Vigencia: 12 Meses.  Para presentar cualquier queja o reclamo, comuníquese al 3108197185  dentro de los primeros 30 días hábiles posteriores la fecha de expedición de la factura.                                                                                                                                                                                
        <br>
        <br>
        Por favor, informe si se encuentra excluido del pago de retención en la fuente según el articulo 369 del estatuto tributario. 
    </p>

</body>

</html>
