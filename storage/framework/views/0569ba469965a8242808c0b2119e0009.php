<div>
    
    
    <div class="card shadow-custom">
        <div class="card-body">
            
            <div class="row">
            
                <div class="col-md-12 mb-3">
                    <h4 class="card-title">Generación de reportes</h4>
                    <p class="card-text">Desde aquí podrás generar los reportes que desees. Sólo se mostrarán los estudios que posean registros en el rango de tiempo ingresado.</p>
                </div>

                <!--[if BLOCK]><![endif]--><?php if(!$ejecutandoReporte & !$reporteListo): ?>
                <div class="col-md-12 mb-3">
                    <p class="card-text">Para iniciar, por favor ingresa un rango de fechas y selecciona el estudio. Por favor completa la información y luego presiona en <b>Generar Reporte</b>. Por seguridad sólo se podrá hacer uso de un intervalo de 15 días.</p>
                </div>
                <div class="col-md-3 mb-3">
                    <label>Fecha de nicio:</label>
                    <input type="date" id="fecha" class="form-control" wire:model="fechaInicio" max="<?php echo e($fechaHoy); ?>">
                </div>
                <div class="col-md-3 mb-3">
                    <label>Fecha de cierre:</label>
                    <input type="date" id="fecha" class="form-control" wire:model="fechaFin" max="<?php echo e($fechaHoy); ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label>Seleccione un tipo:</label>
                    <select class="form-select" aria-label="Default select example" wire:model.change="tipoReporte">
                        <option disabled value="0">Selecciona una opción</option>
                        <option value="1">Ver todos los estudios</option>
                        <option value="2">Ver algún/algunos estudios</option>

                    </select>
                </div>

                
                <!--[if BLOCK]><![endif]--><?php if($tipoReporte =="1"): ?>
                <div class="col-md-2">
                    <br>
                    <button type="button" class="btn btn-outline-primary" wire:click="completarReporte()">Generar reporte</button>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <!--[if BLOCK]><![endif]--><?php if($tipoReporte =="2"): ?>
                <div class="col-md-6 mb-3">
                    <label>Seleccione un estudio para añadir:</label>
                    <select class="form-select" aria-label="Default select example" wire:model="estudioActual">
                        <option disabled value="0" selected>Selecciona una opción</option>
                        <!--[if BLOCK]><![endif]--><?php if(!empty(($informacion))): ?>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $informacion; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index=>$estudio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <!--[if BLOCK]><![endif]--><?php if(!in_array($index,$indexSeleccionados)): ?>
                            <!--[if BLOCK]><![endif]--><?php if($estudio["Active"]): ?>
                            <option value="<?php echo e($index+1); ?>"><?php echo e($estudio["StudyName"]." (".$estudio["City"].")"); ?></option>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                    </select>
                </div>
                <div class="col-md-4">
                    <br>
                    <button type="button" class="btn btn-outline-secondary" wire:click="adicionarEstudio()">Añadir Estudio</button>
                    <button type="button" class="btn btn-outline-primary ms-3" wire:click="completarReporte()">Generar reporte</button>
                </div>
                <div class="col-md-12">
                    <p>Estudios añadidos:</p>
                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $indexSeleccionados; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $indice=>$est): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="badge bg-secondary text-white d-inline-flex align-items-center">
                        <?php echo e($informacion[$est]["StudyName"]." (".$informacion[$est]["City"].")"); ?>

                        <button type="button" class="btn-close btn-close-white btn-sm ms-2" aria-label="Cerrar" wire:click="quitarEstudio(<?php echo e($indice); ?>)"></button>
                    </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                <!--[if BLOCK]><![endif]--><?php if($ejecutandoReporte): ?>
                <div class="col-md-12">
                    <label>Generando reporte...</label>
                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split("progressbar", ["userId" => Auth::user()->id,"functionId"=>"reportProgress","endSignal"=>"progressDone"]);

$__html = app('livewire')->mount($__name, $__params, 'lw-2546590347-0', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                
                <!--[if BLOCK]><![endif]--><?php if($reporteListo): ?>
                <!--[if BLOCK]><![endif]--><?php if($enviandoReportes): ?>
                <div class="col-md-12">
                    <label>Enviando reportes...</label>
                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split("progressbar", ["userId" => Auth::user()->id,"functionId"=>"reportSendProgress","endSignal"=>"progressSendDone"]);

$__html = app('livewire')->mount($__name, $__params, 'lw-2546590347-1', $__slots ?? [], get_defined_vars());

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                </div>
                <br><br>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <div class="col-md-12">
                    
                    <div class="accordion" id="accordionPanelsStayOpenExample">
                        <!--[if BLOCK]><![endif]--><?php if(!empty($resultado)): ?>
                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $resultado; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <!--[if BLOCK]><![endif]--><?php if(array_key_exists("ResultsReport", $item)): ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading<?php echo e($index); ?>">
                                    <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#collapse<?php echo e($index); ?>"
                                            aria-expanded="<?php echo e($index === 0 ? 'true' : 'false'); ?>"
                                            aria-controls="collapse<?php echo e($index); ?>">
                                        Resultados para: <?php echo e($item["StudyName"]." (".$item["City"].")"); ?>

                                    </button>
                                </h2>
                                <div id="collapse<?php echo e($index); ?>" class="accordion-collapse collapse" aria-labelledby="heading<?php echo e($index); ?>" data-bs-parent="#accordionPanelsStayOpenExample">
                                    <div class="accordion-body">
                                        <div class="row">

                                            <style>
                                                .table-container {
                                                  max-width: width: calc(100% - 500px);; /* Asegura que el contenedor no exceda el ancho de la pantalla */
                                                  overflow-x: auto; /* Habilita el scroll horizontal */
                                                  -webkit-overflow-scrolling: touch; /* Mejora la experiencia en dispositivos móviles */
                                                }
                                              </style>

                                            <p><b>Información de acciones por máquinas:</b></p>
                                            <div class="table-container">
                                                <table class="table align-middle text-center">
                                                    <thead>
                                                        <tr>
                                                            <th scope="col" colspan="2">Acción</th>
                                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $item["ResultsReport"]["Maquinas"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Maquina=>$info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <th scope="col">#<?php echo e($Maquina); ?></th>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                            <th scope="col">Total</th>

                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        
                                                        <tr>
                                                            <td rowspan="2">MOV</td>
                                                            <td>Cantidad</td>
                                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $item["ResultsReport"]["Maquinas"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Maquina=>$info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <td><?php echo e($info["Acciones"]["MOV"]["Cantidad"] ?? 0); ?></td>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                            <td><?php echo e($item["ResultsReport"]["Acciones"]["MOV"]["Cantidad"] ?? 0); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Tiempo (min)</td>
                                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $item["ResultsReport"]["Maquinas"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Maquina=>$info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <td><?php echo e(number_format(($info["Acciones"]["MOV"]["Tiempo"] ?? 0) / 60, 2)); ?></td>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                            <td><?php echo e(number_format(($item["ResultsReport"]["Acciones"]["MOV"]["Tiempo"] ?? 0) / 60, 2)); ?></td>
                                                        </tr>

                                                        
                                                        <tr>
                                                            <td rowspan="2">CONTROL</td>
                                                            <td>Cantidad</td>
                                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $item["ResultsReport"]["Maquinas"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Maquina=>$info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <td><?php echo e($info["Acciones"]["CONTROL"]["Cantidad"] ?? 0); ?></td>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                            <td><?php echo e($item["ResultsReport"]["Acciones"]["CONTROL"]["Cantidad"] ?? 0); ?></td>
                                                        </tr>
                                                        <tr>
                                                            <td>Tiempo (min)</td>
                                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $item["ResultsReport"]["Maquinas"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Maquina=>$info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <td><?php echo e(number_format(($info["Acciones"]["CONTROL"]["Tiempo"] ?? 0) / 60, 2)); ?></td>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                            <td><?php echo e(number_format(($item["ResultsReport"]["Acciones"]["CONTROL"]["Tiempo"] ?? 0) / 60, 2)); ?></td>
                                                        </tr>

                                                        
                                                        <tr>
                                                            <td>CUM</td>
                                                            <td>Cantidad</td>
                                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $item["ResultsReport"]["Maquinas"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Maquina=>$info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <td><?php echo e($info["Acciones"]["CUM"]["Cantidad"] ?? 0); ?></td>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                            <td><?php echo e($item["ResultsReport"]["Acciones"]["CUM"]["Cantidad"] ?? 0); ?></td>
                                                        </tr>

                                                        
                                                        <tr>
                                                            <td>SCUM</td>
                                                            <td>Cantidad</td>
                                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $item["ResultsReport"]["Maquinas"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Maquina=>$info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <td><?php echo e($info["Acciones"]["SCUM"]["Cantidad"] ?? 0); ?></td>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                            <td><?php echo e($item["ResultsReport"]["Acciones"]["SCUM"]["Cantidad"] ?? 0); ?></td>
                                                        </tr>

                                                        
                                                        <tr>
                                                            <td>XCUM</td>
                                                            <td>Cantidad</td>
                                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $item["ResultsReport"]["Maquinas"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Maquina=>$info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <td><?php echo e($info["Acciones"]["XCUM"]["Cantidad"] ?? 0); ?></td>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                            <td><?php echo e($item["ResultsReport"]["Acciones"]["XCUM"]["Cantidad"] ?? 0); ?></td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </div>
                                            <br>
                                            <p><b>Información de tokens por páginas y modelos:</b></p>

                                            <table class="table align-middle text-center">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Modelo</th>
                                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $item["ResultsReport"]["Paginas"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pagina=>$info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <th scope="col"><?php echo e($pagina == "SIMULADOR" ? "MANUAL" : $pagina); ?></th>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                        <th scope="col">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $item["ResultsReport"]["Modelos"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modelo=>$info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e($modelo); ?></td>
                                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $item["ResultsReport"]["Paginas"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pagina=>$_): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <td><?php echo e($info["Paginas"][$pagina]["Tokens"]??0); ?></td>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                        <td><?php echo e($info["Tokens"]??0); ?></td>

                                                    </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                    <tr>
                                                        <th scope="row">Total</th>
                                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $item["ResultsReport"]["Paginas"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pagina=>$info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <td><?php echo e($info["Tokens"]??0); ?></td>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                        <th scope="row"><?php echo e($item["ResultsReport"]["Tokens"]); ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <br>
                                            <p><b>Información de acciones por modelos:</b></p>

                                            <table class="table align-middle text-center">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Modelo</th>
                                                        <th scope="col">MOV (min)</th>
                                                        <th scope="col">CONTROL (min)</th>
                                                        <th scope="col">CUM</th>
                                                        <th scope="col">SCUM</th>
                                                        <th scope="col">XCUM</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $item["ResultsReport"]["Modelos"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modelo=>$info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e($modelo); ?></td>
                                                        <td><?php echo e(number_format(($info["Acciones"]["MOV"]["Tiempo"] ?? 0) / 60, 2)); ?></td>
                                                        <td><?php echo e(number_format(($info["Acciones"]["CONTROL"]["Tiempo"] ?? 0) / 60, 2)); ?></td>
                                                        <td><?php echo e($info["Acciones"]["CUM"]["Cantidad"] ?? 0); ?></td>
                                                        <td><?php echo e($info["Acciones"]["SCUM"]["Cantidad"] ?? 0); ?></td>
                                                        <td><?php echo e($info["Acciones"]["XCUM"]["Cantidad"] ?? 0); ?></td>
                                                    </tr>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                    <tr>
                                                        <th scope="row">Total</th>
                                                        <td><?php echo e(number_format(($item["ResultsReport"]["Acciones"]["MOV"]["Tiempo"] ?? 0) / 60, 2)); ?></td>
                                                        <td><?php echo e(number_format(($item["ResultsReport"]["Acciones"]["CONTROL"]["Tiempo"] ?? 0) / 60, 2)); ?></td>
                                                        <td><?php echo e($item["ResultsReport"]["Acciones"]["CUM"]["Cantidad"] ?? 0); ?></td>
                                                        <td><?php echo e($item["ResultsReport"]["Acciones"]["SCUM"]["Cantidad"] ?? 0); ?></td>
                                                        <td><?php echo e($item["ResultsReport"]["Acciones"]["XCUM"]["Cantidad"] ?? 0); ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>

                                            <br>
                                            <p><b>Información de tiempo (horas) de conexión de modelos por máquina:</b></p>
                                            <div class="table-container">
                                                <table class="table align-middle text-center">
                                                    <!--[if BLOCK]><![endif]--><?php if(!empty($item["ConReport"]["Maquinas"])): ?>
                                                    <thead>
                                                        <tr>
                                                            <th scope="col">Modelo</th>
                                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $item["ConReport"]["Maquinas"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Maquina => $Tiempo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <th scope="col">#<?php echo e($Maquina); ?></th>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                            <th scope="col">Total</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $item["ConReport"]["Modelos"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Modelo => $DataModelo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <tr>
                                                                <td><?php echo e($Modelo); ?></td>
                                                                
                                                                <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $item["ConReport"]["Maquinas"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Maquina => $Tiempo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <td><?php echo e(number_format(($DataModelo["Maquinas"][$Maquina] ?? 0) / 3600, 2)); ?></td>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->

                                                                <td><?php echo e(number_format($DataModelo["Total"] / 3600, 2)); ?></td>
                                                            </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                        <tr>
                                                            <td><b>Total:</b></td>
                                                            <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $item["ConReport"]["Maquinas"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $Maquina => $Tiempo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <td><?php echo e(number_format($Tiempo / 3600, 2)); ?></td>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                            <td><b><?php echo e(number_format($item["ConReport"]["Total"] / 3600, 2)); ?></b></td>
                                                        </tr>
                                                    </tbody>
                                                    <?php else: ?>
                                                    <tr class="text-center">
                                                        <td colspan="100%">No se encontraron registros de tiempo de conexión en este periodo</td>
                                                    </tr>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                </table>
                                            </div>


                                            <!--[if BLOCK]><![endif]--><?php if($item["Renta"]=="Compartida"): ?>
                                            <br>
                                            <p><b>Información de cobros por acciones:</b></p>

                                            <table class="table align-middle text-center">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Acción</th>
                                                        <th scope="col">Cantidad</th>
                                                        <th scope="col">Valor unidad</th>
                                                        <th scope="col">Total</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $item["CobrosTotales"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action=>$info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <!--[if BLOCK]><![endif]--><?php if($action!="Total"): ?>
                                                    <tr>
                                                        <td><?php echo e($action); ?></td>
                                                        <!--[if BLOCK]><![endif]--><?php if($action=="MOV" || $action=="CONTROL"): ?>
                                                        <td>$<?php echo e(number_format(($item["ResultsReport"]["Acciones"][$action]["Tiempo"] ?? 0) / 60, 2)); ?></td>
                                                        <?php else: ?>
                                                        <td>$<?php echo e(number_format(($item["ResultsReport"]["Acciones"][$action]["Cantidad"] ?? 0), 2)); ?></td>
                                                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                        <td>$<?php echo e(number_format(($item["Montos"][$action] ?? 0), 2)); ?></td>
                                                        <td>$<?php echo e(number_format(($info ?? 0), 2)); ?></td>
                                                    </tr>
                                                    <?php else: ?>
                                                    <tr>
                                                        <td colspan="2" style="border:0;"></td>
                                                        <td><b><?php echo e($action); ?></b></td>
                                                        <td>$<?php echo e(number_format(($info ?? 0), 2)); ?></td>
                                                    </tr>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                </tbody>
                                            </table>

                                            <br>
                                            <p><b>Información de cobros por acciones por modelos:</b></p>

                                            <table class="table align-middle text-center">
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
                                                    <!--[if BLOCK]><![endif]--><?php $__currentLoopData = $item["CobrosModelos"]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $modelo=>$info): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <!--[if BLOCK]><![endif]--><?php if($modelo!="Total"): ?>
                                                    <tr>
                                                        <td><b><?php echo e($modelo); ?></b></td>
                                                        <td>$<?php echo e(number_format(($info["MOV"] ?? 0), 2)); ?></td>
                                                        <td>$<?php echo e(number_format(($info["CONTROL"] ?? 0), 2)); ?></td>
                                                        <td>$<?php echo e(number_format(($info["CUM"] ?? 0), 2)); ?></td>
                                                        <td>$<?php echo e(number_format(($info["SCUM"] ?? 0), 2)); ?></td>
                                                        <td>$<?php echo e(number_format(($info["XCUM"] ?? 0), 2)); ?></td>
                                                        <td>$<?php echo e(number_format(($info["Total"] ?? 0), 2)); ?></td>
                                                    </tr>
                                                    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                                                    <tr>
                                                        <td><b>Total</b></td>
                                                        <td>$<?php echo e(number_format(($item["CobrosTotales"]["MOV"] ?? 0), 2)); ?></td>
                                                        <td>$<?php echo e(number_format(($item["CobrosTotales"]["CONTROL"] ?? 0), 2)); ?></td>
                                                        <td>$<?php echo e(number_format(($item["CobrosTotales"]["CUM"] ?? 0), 2)); ?></td>
                                                        <td>$<?php echo e(number_format(($item["CobrosTotales"]["SCUM"] ?? 0), 2)); ?></td>
                                                        <td>$<?php echo e(number_format(($item["CobrosTotales"]["XCUM"] ?? 0), 2)); ?></td>
                                                        <td><b>$<?php echo e(number_format(($item["CobrosTotales"]["Total"] ?? 0), 2)); ?></b></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><!--[if ENDBLOCK]><![endif]-->
                        <?php else: ?>
                        ERROR GENERANDO
                        <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                    </div>
                </div>
                <!--[if BLOCK]><![endif]--><?php if(str_contains(url()->full(), 'localhost')): ?>
                <div class="col-md-12 mt-2 d-grid">
                    <button class="btn btn-outline-primary" wire:click="enviarTodosReportes()">Enviar todos los reportes</button>
                </div>
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->
                <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

            </div>
        </div>
    </div>


    <br>

    <script>
        document.addEventListener('livewire:init', () => {
            Livewire.on('abrir-reporte', (event) => {
                // Aquí accedemos a los datos necesarios
                const url = event[0]["url"];  // La URL base sin parámetros
                const data = event[0]["data"];
                // const otherData = event[0]["otherData"];  // Si tienes otros datos
    
                // Creamos el formulario
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;  // Usamos la URL base
                form.target = '_blank';  // Abre en nueva ventana

                // Campo CSRF
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '<?php echo e(csrf_token()); ?>';
                form.appendChild(csrfToken);

                // Campo title (ahora como POST)
                const dataInput = document.createElement('input');
                dataInput.type = 'hidden';
                dataInput.name = 'data';
                dataInput.value = data;
                form.appendChild(dataInput);

                // Enviamos el formulario
                document.body.appendChild(form);
                form.submit();
                form.remove();
            });
        });
    </script>


</div><?php /**PATH C:\xampp\htdocs\RobboAdminPanel\panel\resources\views/livewire/estudios/reporte.blade.php ENDPATH**/ ?>