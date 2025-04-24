<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>PDF de ejemplo</title>
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
                    2024/04/24
                    <br>
                    <b style="color:#c84b46; font-size: 25px; padding-top: 50px;">
                        REPORTE GENERAL
                    </b>
                    <br>
                    <b>Periodo: </b> Abril 01 - Abril 15
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
                        NIT. 901.389.093<br>
                        Calle 103 # 45A - 14<br>
                        BOGOTÁ, COLOMBIA<br>
                        310 819 7185
                    </span>
                </p>
            </td>
            <td>
                <td style="width: 50%">
                    <p>
                        <b style="color:#c84b46; font-size: 16px;">Destinatario:</b><br>
                        <b>COOLSOFT TECHNOLOGY SAS</b><br>
                        <span style="font-size: 12px;">
                            NIT. 901.389.093<br>
                            Calle 103 # 45A - 14<br>
                            BOGOTÁ, COLOMBIA<br>
                            310 819 7185
                        </span>
                    </p>
            </td>
        </tr>
    </table>

    <div class="page-break"></div>

</body>
</html>
