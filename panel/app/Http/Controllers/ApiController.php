<?php

namespace App\Http\Controllers;

use App\Models\BusinessModelHistory;
use App\Models\MachineHistory;
use Illuminate\Bus\BusServiceProvider;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function dashboard(Request $request)
    {
        $datas = $request->all()['params'] ?? null;

        // Valido que lleguen todos los campos
        if (!isset($datas['command']) || !isset($datas['code']) || !isset($datas['data'])) {
            return response()->json([
                'success' => false,
                'message' => 'Estructura incorrecta, faltan campos requeridos',
                'timestamp' => now()->toDateTimeString(),
                'error'=>$datas
            ], 403);
        }


        //Valido que el comando que llegue sea el correcto
        if ($datas['code'] != '123ABC') {
            return response()->json([
                'success' => false,
                'message' => 'Código inválido o faltante',
                'timestamp' => now()->toDateTimeString(),
            ], 403);
        }

        //Ahora, con lo anterior, valido el comando que llega
        switch($datas['command']){
            case 'getMachineInformation':

                //Valido que los campos de mi interés lleguen
                if(!isset($datas['data']['machine'])){
                    return response()->json([
                        'success' => false,
                        'message' => 'Faltan campos en la consulta',
                        'timestamp' => now()->toDateTimeString(),
                    ], 403);
                }

                //Obtengo la información de la máquina
                $firmwareMaquina=$datas['data']['machine'];

                $data_send=[
                    'Branch' => 'Server',
                    'Service' => 'Machines',
                    'Action' => 'OneView',
                    'Data' => [
                        "UserId" => "1",
                        "Machines"=>[
                            ["FirmwareID"=>$firmwareMaquina]
                        ]
                    ]
                ];
                $data=sendBack(data:$data_send,produccionForced: true);

                //Analizo que toda la data llegue bien
                if (isset($data['Status'])) {
                    //Analizo si el status es true
                    if($data["Status"]){

                        //Extraigo la Id de la máquina
                        $machineId = $data['Data']['Machines'][0]['ID'] ?? null;

                        if($machineId){
                            //Obtengo los ultimos 10 movimientos
                            $movimientos_all=MachineHistory::where('machine_id', $machineId)
                                ->orderBy('created_at', 'desc')
                                ->take(10)
                                ->get();

                            $mantenimientos=[];

                            //Recorro y formateo
                            foreach($movimientos_all as $movimiento) {
                                $mantenimientos[] = [
                                    'description' => $movimiento->description,
                                    'author' => $movimiento->author_info->name ?? 'Desconocido',
                                    'created_at' => $movimiento->created_at->format('Y-m-d H:i:s'),
                                ];
                            }

                            //COnsulto si hay elementos o no en compra fija
                            $compra=BusinessModelHistory::where("id_study", $data['Data']['Machines'][0]['StudyData']["Id"] ?? null)
                                ->where("environment", session('API_used', "production"))
                                ->exists();

                            return response()->json([
                                'success' => true,
                                'command'=>'getMachineInformation',
                                'timestamp' => now()->toDateTimeString(),
                                'data' => [
                                    'machineFirmware' => $firmwareMaquina,
                                    'mantenimientos'=>$mantenimientos,
                                    'autoclean'=>$data['Data']['Machines'][0]['AutoClean'] ?? [],
                                    'studymodel'=>$compra
                                ],
                                // 'machineFirmware'=>$datas['machine'],
                                // 'ranks'=>$datamod["Data"]["ModelData"]["RangeValues"]??[]
                                // 'other'=>$data
                            ]);
                        }

                    }
                }

                return response()->json([
                    'success' => false,
                    'message' => 'No fue posible obtener la información',
                    'command'=>'getMachineInformation',
                    'data' => [
                        'machineFirmware' => $firmwareMaquina
                    ],
                    'timestamp' => now()->toDateTimeString(),
                ], 403);

                break;
            case 'getModelInformation':

                //Valido que los campos de mi interés lleguen
                if(!isset($datas['data']['machine']) || !isset($datas['data']['model'])){
                    return response()->json([
                        'success' => false,
                        'message' => 'Faltan campos en la consulta',
                        'timestamp' => now()->toDateTimeString(),
                    ]);
                }

                //Obtengo la información de la máquina y modelo
                $firmwareMaquina=$datas['data']['machine'];
                $modelId=$datas['data']['model'];

                //Genero la consulta para obtener los rangos del modelo
                $data_sendmod=[
                    'Branch' => 'Server',
                    'Service' => 'SelfModels',
                    'Action' => 'GetConfig',
                    'Data' => [
                        "UserId" => "1",
                        "ModelData"=>[
                            "ModelId"=>$modelId
                        ],
                        "UserData"=>[
                            "Id"=>1
                        ]
                    ]


                ];
                $data=sendBack(data:$data_sendmod,produccionForced: true);

                //Consulto si se obtuvieron todos los valores
                if (isset($data['Status'])) {
                    //Analizo si el status es true
                    if($data["Status"]){
                        $rangeValues =$data["Data"]["ModelData"]["RangeValues"]??[];

                        usort($rangeValues, function($a, $b) {
                            return $a['tokens'] <=> $b['tokens']; // For ascending order
                            // return $b['tokens'] <=> $a['tokens']; // For descending order
                        });

                        return response()->json([
                            'success' => true,
                            'command'=>'getModelInformation',
                            'timestamp' => now()->toDateTimeString(),
                            'data' => [
                                'machineFirmware' => $firmwareMaquina,
                                'modelRanks'=>$rangeValues
                            ],
                            // 'other'=>$data
                        ]);
                    }
                }

                return response()->json([
                    'success' => false,
                    'message' => 'No fue posible obtener la información',
                    'command'=>'getModelInformation',
                    'data' => [
                        'machineFirmware' => $firmwareMaquina,
                        'modelId'=>$modelId
                    ],
                    'timestamp' => now()->toDateTimeString(),
                ], 403);

                break;

            case 'getTimeInformation':
                $now = now();

                $primerDiaQuincena = $now->copy()->startOfMonth(); // Empezamos desde el inicio del mes

                if ($now->day > 15) {
                    // Si estamos en la segunda quincena, el primer día es el 16
                    $primerDiaQuincena = $primerDiaQuincena->day(16);
                }

                $diaInicial=$primerDiaQuincena->format('Y-m-d'); // Formato YYYY-MM-DD


                $ultimoDiaQuincena = $now->copy()->endOfMonth(); // Empezamos desde el fin del mes

                if ($now->day <= 15) {
                    // Si estamos en la primera quincena, el último día es el 15
                    $ultimoDiaQuincena = $ultimoDiaQuincena->day(15);
                }

                $diaFinal=$ultimoDiaQuincena->format('Y-m-d'); // Formato YYYY-MM-DD

                $data_send=[
                    'Branch' => 'Server',
                    'Service' => 'PlatformUser',
                    'Action' => 'CxTimeReport',
                    'Data' => [
                        "UserId" => "1",
                        "ModelData"=>[
                            "InitialDate"=>$diaInicial,
                            "FinalDate"=>$diaFinal
                        ],
                        "UserData"=>[
                            "Id"=>1
                        ]
                    ]


                ];

                $data=sendBack(data:$data_send,produccionForced: true);

                if (isset($data['Status'])) {
                    //Analizo si el status es true
                    if($data["Status"]){

                        $tiempos=[];
                        $modelos=[];
                        foreach ($data['Data']['CxTimeArchives'] as $time) {

                            $tiempos[] = [
                                'date' => $time['ArchiveDate'],
                                'archive'=> json_decode(base64_decode($time['Archive'])),
                            ];
                        }

                        foreach ($data['Data']['CxTimeArchives'] as $time) {
                            if($time['ArchiveExist']){
                                //Descifro el archivo
                                $archivos=json_decode(base64_decode($time['Archive']),true);

                                //Recorro los archivos
                                foreach($archivos as $archivo){
                                    //Recorro las maquinas
                                    foreach($archivo["Maquinas"] as $maquina){
                                        //Recorro los modelos
                                        foreach($maquina["Modelos"] as $modeloid=>$modelotime){
                                            //Si el modelo no existe, lo creo
                                            if(!isset($modelos[$modeloid])){
                                                $modelos[$modeloid]=0;
                                            }

                                            //AÑado el tiempo
                                            $modelos[$modeloid]+=$modelotime;
                                        }
                                    }
                                }
                            }

                        }

                        return response()->json([
                            'success' => true,
                            'command'=>'getTimeInformation',
                            'timestamp' => now()->toDateTimeString(),
                            'data' => [
                                // 'times' => $tiempos,
                                'models' => $modelos,
                            ],
                            // 'other'=>$data
                        ]);
                    }
                }


                break;

            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Comando no reconocido',
                    'timestamp' => now()->toDateTimeString(),
                ], 403);
        }
    }
}
