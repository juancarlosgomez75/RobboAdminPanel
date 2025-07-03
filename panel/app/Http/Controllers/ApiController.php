<?php

namespace App\Http\Controllers;

use App\Models\MachineHistory;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function dashboard(Request $request)
    {
        $datas = $request->all()['params'] ?? null;

        // Validar que el campo 'code' sea exactamente '123ABC'
        if (!isset($datas['code']) || $datas['code'] != '123ABC') {
            return response()->json([
                'status' => 'error',
                'message' => 'Código inválido o faltante.',
            ], 403);
        }

        $data_send=[
            'Branch' => 'Server',
            'Service' => 'Machines',
            'Action' => 'OneView',
            'Data' => [
                "UserId" => "1",
                "Machines"=>[
                    ["FirmwareID"=>$datas['machine'] ]
                ]
            ]
        ];
        $data=sendBack($data_send);

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

                    return response()->json([
                        'success' => true,
                        'command'=>'getMantenimientos',
                        'timestamp' => now()->toDateTimeString(),
                        'data' => $mantenimientos,
                        'machineFirmware'=>$datas['machine'],
                        'autoclean'=>$data['Data']['Machines'][0]['AutoClean'] ?? false,
                        // 'other'=>$data
                    ]);
                }

            }
        }

        return response()->json([
            'message' => 'No Autorizado, datos recibidos.',
            'status' => 'success',
            'timestamp' => now()->toDateTimeString(),
            'data' => [
                'command' => $data['command'] ?? null,
                'machine' => $data['machine'] ?? null,
            ]
        ]);
    }
}
