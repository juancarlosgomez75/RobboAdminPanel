<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="<?php echo e(asset('img/bb_fondo.png')); ?>">
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
                <img src="data:image/jpeg;base64,<?php echo e(base64_encode(file_get_contents(public_path('img/Logo.jpg')))); ?>" alt="Logo">
            </td>
            <td style="text-align: center">
                <p>
                    <?php echo e($fechaActual); ?>

                    <br>
                    <b style="color:#c84b46; font-size: 25px; padding-top: 50px;">
                        REPORTE GENERAL
                    </b>
                    <br>
                    <b>Periodo: </b> <?php echo e(ucfirst($fechaInicio)); ?> - <?php echo e(ucfirst($fechaFin)); ?>

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
                        <b><?php echo e($data["RazonSocial"]??"No encontrado"); ?></b><br>
                        <span style="font-size: 12px;">
                            NIT. <?php echo e($data["Nit"]??"No encontrado"); ?><br>
                            <?php echo e($data["Address"]??"No encontrado"); ?><br>
                            <?php echo e($data["City"]??"No encontrado"); ?><br>
                            <?php echo e($data["Phone"]??"No encontrado"); ?>

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
    <?php
    //Cuento las máquinas
    $chunks=ceil(count($data["ResultsReport"]["Maquinas"])/8);
    
    ?>

    <?php for($i = 0; $i < $chunks; $i++): ?>
    <table class="report-table">
        <thead>
            <tr>
                <th scope="col" colspan="2">Acción</th>
                <?php $__currentLoopData = array_slice($data["ResultsReport"]["Maquinas"], $i*8, 8, true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Maquina=>$info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <th scope="col">#<?php echo e($Maquina); ?></th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                <?php if($i==$chunks-1): ?>
                <th scope="col">Total</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
            
            <tr>
                <td rowspan="2">MOV</td>
                <td>Cantidad</td>
                <?php $__currentLoopData = array_slice($data["ResultsReport"]["Maquinas"], $i*8, 8, true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Maquina=>$info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <td><?php echo e($info["Acciones"]["MOV"]["Cantidad"] ?? 0); ?></td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($i==$chunks-1): ?>
                <td><?php echo e($data["ResultsReport"]["Acciones"]["MOV"]["Cantidad"] ?? 0); ?></td>
                <?php endif; ?>
            </tr>
            <tr>
                <td>Tiempo (Minu)</td>
                <?php $__currentLoopData = array_slice($data["ResultsReport"]["Maquinas"], $i*8, 8, true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Maquina=>$info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <td><?php echo e(number_format(($info["Acciones"]["MOV"]["Tiempo"] ?? 0) / 60, 2)); ?></td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($i==$chunks-1): ?>
                <td><?php echo e(number_format(($data["ResultsReport"]["Acciones"]["MOV"]["Tiempo"] ?? 0) / 60, 2)); ?></td>
                <?php endif; ?>
            </tr>

            
            <tr>
                <td rowspan="2">CONTROL</td>
                <td>Cantidad</td>
                <?php $__currentLoopData = array_slice($data["ResultsReport"]["Maquinas"], $i*8, 8, true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Maquina=>$info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <td><?php echo e($info["Acciones"]["CONTROL"]["Cantidad"] ?? 0); ?></td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($i==$chunks-1): ?>
                <td><?php echo e($data["ResultsReport"]["Acciones"]["CONTROL"]["Cantidad"] ?? 0); ?></td>
                <?php endif; ?>
            </tr>
            <tr>
                <td>Tiempo (Minu)</td>
                <?php $__currentLoopData = array_slice($data["ResultsReport"]["Maquinas"], $i*8, 8, true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Maquina=>$info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <td><?php echo e(number_format(($info["Acciones"]["CONTROL"]["Tiempo"] ?? 0) / 60, 2)); ?></td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($i==$chunks-1): ?>
                <td><?php echo e(number_format(($data["ResultsReport"]["Acciones"]["CONTROL"]["Tiempo"] ?? 0) / 60, 2)); ?></td>
                <?php endif; ?>
            </tr>
            
            <tr>
                <td>CUM</td>
                <td>Cantidad</td>
                <?php $__currentLoopData = array_slice($data["ResultsReport"]["Maquinas"], $i*8, 8, true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Maquina=>$info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <td><?php echo e($info["Acciones"]["CUM"]["Cantidad"] ?? 0); ?></td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($i==$chunks-1): ?>
                <td><?php echo e($data["ResultsReport"]["Acciones"]["CUM"]["Cantidad"] ?? 0); ?></td>
                <?php endif; ?>
            </tr>

            
            <tr>
                <td>SCUM</td>
                <td>Cantidad</td>
                <?php $__currentLoopData = array_slice($data["ResultsReport"]["Maquinas"], $i*8, 8, true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Maquina=>$info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <td><?php echo e($info["Acciones"]["SCUM"]["Cantidad"] ?? 0); ?></td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($i==$chunks-1): ?>
                <td><?php echo e($data["ResultsReport"]["Acciones"]["SCUM"]["Cantidad"] ?? 0); ?></td>
                <?php endif; ?>
            </tr>

            
            <tr>
                <td>XCUM</td>
                <td>Cantidad</td>
                <?php $__currentLoopData = array_slice($data["ResultsReport"]["Maquinas"], $i*8, 8, true); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Maquina=>$info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <td><?php echo e($info["Acciones"]["XCUM"]["Cantidad"] ?? 0); ?></td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <?php if($i==$chunks-1): ?>
                <td><?php echo e($data["ResultsReport"]["Acciones"]["XCUM"]["Cantidad"] ?? 0); ?></td>
                <?php endif; ?>
            </tr>
        </tbody>
    </table>
    <br>
    <?php endfor; ?>
    <br>


    <b>Información de tokens por páginas y modelos:</b><br>
    <table class="report-table">
        <thead>
            <tr>
                <th scope="col">Modelo</th>
                <?php $__currentLoopData = $data["ResultsReport"]["Paginas"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pagina=>$info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <th scope="col"><?php echo e($pagina == "SIMULADOR" ? "MANUAL" : $pagina); ?></th>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <th scope="col">Total</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $data["ResultsReport"]["Modelos"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modelo=>$info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($modelo); ?></td>
                <?php $__currentLoopData = $data["ResultsReport"]["Paginas"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pagina=>$_): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <td><?php echo e($info["Paginas"][$pagina]["Tokens"]??0); ?></td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <td><?php echo e($info["Tokens"]??0); ?></td>

            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><b>Total</b></td>
                <?php $__currentLoopData = $data["ResultsReport"]["Paginas"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pagina=>$info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <td><?php echo e($info["Tokens"]??0); ?></td>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                <td><b><?php echo e($data["ResultsReport"]["Tokens"]); ?></b></td>
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
            <?php $__currentLoopData = $data["ResultsReport"]["Modelos"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modelo=>$info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($modelo); ?></td>
                <td><?php echo e(number_format(($info["Acciones"]["MOV"]["Tiempo"] ?? 0) / 60, 2)); ?></td>
                <td><?php echo e(number_format(($info["Acciones"]["CONTROL"]["Tiempo"] ?? 0) / 60, 2)); ?></td>
                <td><?php echo e($info["Acciones"]["CUM"]["Cantidad"] ?? 0); ?></td>
                <td><?php echo e($info["Acciones"]["SCUM"]["Cantidad"] ?? 0); ?></td>
                <td><?php echo e($info["Acciones"]["XCUM"]["Cantidad"] ?? 0); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><b>Total</b></td>
                <td><?php echo e(number_format(($data["ResultsReport"]["Acciones"]["MOV"]["Tiempo"] ?? 0) / 60, 2)); ?></td>
                <td><?php echo e(number_format(($data["ResultsReport"]["Acciones"]["CONTROL"]["Tiempo"] ?? 0) / 60, 2)); ?></td>
                <td><?php echo e($data["ResultsReport"]["Acciones"]["CUM"]["Cantidad"] ?? 0); ?></td>
                <td><?php echo e($data["ResultsReport"]["Acciones"]["SCUM"]["Cantidad"] ?? 0); ?></td>
                <td><?php echo e($data["ResultsReport"]["Acciones"]["XCUM"]["Cantidad"] ?? 0); ?></td>
            </tr>
        </tbody>
    </table>
    
    <?php if($data["Renta"]=="Compartida"): ?>

    <div class="page-break"></div>

    <table>
        <tr>
            <td style="width: 50%">
                <img src="data:image/jpeg;base64,<?php echo e(base64_encode(file_get_contents(public_path('img/Logo.jpg')))); ?>" alt="Logo">
            </td>
            <td style="text-align: center">
                <p>
                    <?php echo e($fechaActual); ?>

                    <br>
                    <b style="color:#c84b46; font-size: 25px; padding-top: 50px;">
                        REPORTE DE USO
                    </b>
                    <br>
                    <b>Periodo: </b> <?php echo e(ucfirst($fechaInicio)); ?> - <?php echo e(ucfirst($fechaFin)); ?>

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
                        <b><?php echo e($data["RazonSocial"]??"No encontrado"); ?></b><br>
                        <span style="font-size: 12px;">
                            NIT. <?php echo e($data["Nit"]??"No encontrado"); ?><br>
                            <?php echo e($data["Address"]??"No encontrado"); ?><br>
                            <?php echo e($data["City"]??"No encontrado"); ?><br>
                            <?php echo e($data["Phone"]??"No encontrado"); ?>

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

            
            <tr>
                <td>MOV (minutos)</td>
                <td><?php echo e(number_format(($data["ResultsReport"]["Acciones"]["MOV"]["Tiempo"] ?? 0) / 60, 2)); ?></td>
                <td><?php echo e('$' . number_format($data["Montos"]["MOV"], 2)); ?></td>
                <td><?php echo e('$' . number_format($data["CobrosTotales"]["MOV"], 2)); ?></td>
                
                
            </tr>

            
            <tr>
                <td>CONTROL (minutos)</td>
                <td><?php echo e(number_format(($data["ResultsReport"]["Acciones"]["CONTROL"]["Tiempo"] ?? 0) / 60, 2)); ?></td>
                <td><?php echo e('$' . number_format($data["Montos"]["CONTROL"], 2)); ?></td>
                <td><?php echo e('$' . number_format($data["CobrosTotales"]["CONTROL"], 2)); ?></td>
            </tr>

            
            <tr>
                <td>CUM</td>
                <td><?php echo e($data["ResultsReport"]["Acciones"]["CUM"]["Cantidad"] ?? 0); ?></td>
                <td><?php echo e('$' . number_format($data["Montos"]["CUM"], 2)); ?></td>
                <td><?php echo e('$' . number_format($data["CobrosTotales"]["CUM"], 2)); ?></td>
            </tr>

            
            <tr>
                <td>SCUM</td>
                <td><?php echo e($data["ResultsReport"]["Acciones"]["SCUM"]["Cantidad"] ?? 0); ?></td>
                <td><?php echo e('$' . number_format($data["Montos"]["SCUM"], 2)); ?></td>
                <td><?php echo e('$' . number_format($data["CobrosTotales"]["SCUM"], 2)); ?></td>
            </tr>

            
            <tr>
                <td>XCUM</td>
                <td><?php echo e($data["ResultsReport"]["Acciones"]["XCUM"]["Cantidad"] ?? 0); ?></td>
                <td><?php echo e('$' . number_format($data["Montos"]["XCUM"], 2)); ?></td>
                <td><?php echo e('$' . number_format($data["CobrosTotales"]["XCUM"], 2)); ?></td>
            </tr>
            <tr>
                <td colspan="2" style="border:0"></td>
                <td>
                    <b>Total: </b>
                </td>
                <td>
                    <b><?php echo e('$' . number_format($data["CobrosTotales"]["Total"], 2)); ?></b>
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
            <?php $__currentLoopData = $data["CobrosModelos"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modelo=>$info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($modelo); ?></td>
                <td><?php echo e('$' . number_format($info["MOV"], 2)); ?></td>
                <td><?php echo e('$' . number_format($info["CONTROL"], 2)); ?></td>
                <td><?php echo e('$' . number_format($info["CUM"], 2)); ?></td>
                <td><?php echo e('$' . number_format($info["SCUM"], 2)); ?></td>
                <td><?php echo e('$' . number_format($info["XCUM"], 2)); ?></td>
                <td><?php echo e('$' . number_format($info["Total"], 2)); ?></td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
            <tr>
                <td><b>Total</b></td>
                <td><?php echo e('$' . number_format($data["CobrosTotales"]["MOV"], 2)); ?></td>
                <td><?php echo e('$' . number_format($data["CobrosTotales"]["CONTROL"], 2)); ?></td>
                <td><?php echo e('$' . number_format($data["CobrosTotales"]["CUM"], 2)); ?></td>
                <td><?php echo e('$' . number_format($data["CobrosTotales"]["SCUM"], 2)); ?></td>
                <td><?php echo e('$' . number_format($data["CobrosTotales"]["XCUM"], 2)); ?></td>
                <td><b><?php echo e('$' . number_format($data["CobrosTotales"]["Total"], 2)); ?></b></td>
            </tr>
        </tbody>

    </table>


    <?php endif; ?>

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
            <?php $__currentLoopData = $data["DetailedReport"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($log["ModelData"]["ModelUserName"]); ?></td>
                <td><?php echo e($log["Action"]); ?></td>
                <td><?php echo e($log["ContratedValue"]); ?></td>
                <td><?php echo e($log["Tokens"]); ?></td>
                <td><?php echo e($log["Typer"]); ?></td>
                <td><?php echo e(\Carbon\Carbon::createFromTimestamp($log["StartDate"] / 1000)->toDateTimeString()); ?></td>
                <td><?php echo e(\Carbon\Carbon::createFromTimestamp($log["EndDate"] / 1000)->toDateTimeString()); ?></td>
                
                
                
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <div class="page-break"></div>

    <b style="color:#c84b46; font-size: 16px;">Aviso legal sobre facturación electrónica y plazos de pago:</b><br>
    <p style="font-size: 12px; text-align: justify;">
        Nos permitimos informar a nuestros clientes que, conforme a nuestras políticas comerciales y en cumplimiento de la normativa vigente en materia de facturación electrónica, todas las facturas generadas por la adquisición de nuestros bienes y/o servicios serán enviadas al correo electrónico registrado previamente en la plataforma SIIGO. Este envío se efectuará dentro de un plazo máximo de cinco (5) días hábiles contados a partir de la fecha de realización de la operación comercial correspondiente.
        <br><br>
        Es responsabilidad del cliente asegurarse de que el correo electrónico suministrado en el sistema esté actualizado, activo y sea accesible, con el fin de garantizar la correcta recepción de los documentos electrónicos. Cualquier error en los datos de contacto que impida la recepción oportuna de la factura será responsabilidad exclusiva del cliente, por lo cual recomendamos revisar y confirmar que la información registrada en el sistema sea correcta.
        <br><br>
        Una vez recibida la factura electrónica, el cliente dispondrá de un plazo máximo de cinco (5) días hábiles, contados a partir de la fecha de recepción del documento, para efectuar el pago correspondiente en los términos y condiciones acordados. El incumplimiento en el pago dentro del plazo establecido podrá dar lugar a la aplicación de intereses moratorios, la suspensión de servicios, o cualquier otra medida que la empresa considere pertinente, conforme a lo establecido en el contrato de prestación de servicios o en las condiciones comerciales previamente aceptadas.
        <br><br>
        Agradecemos su atención a este aviso y su colaboración para el cumplimiento oportuno de las obligaciones derivadas de nuestras relaciones comerciales.
        <br><br>
        Este reporte tiene únicamente fines informativos y no constituye una factura electrónica. El presente documento se genera con el objetivo de brindar al cliente un resumen detallado de la transacción realizada y facilitar el seguimiento de la operación. No tiene validez comercial, fiscal ni contable, y no puede utilizarse como soporte para efectos tributarios o financieros.
        <br><br>
        Para cualquier duda o inquietud relacionada con el proceso de facturación o el estado de su cuenta, puede comunicarse con nuestro equipo de soporte a través de los canales oficiales de atención al cliente.
    </p>

</body>

</html>
<?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views/report_template.blade.php ENDPATH**/ ?>