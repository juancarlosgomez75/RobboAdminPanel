<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function dashboard(Request $request){
        // Return any test data as an associative array, which Laravel will convert to JSON.
        $data = $request->all();
        return response()->json([
            'message' => 'Data from Laravel API!',
            'status' => 'success',
            'timestamp' => now()->toDateTimeString(), // Current time in Y-m-d H:i:s format
            'data' => [
                'item1' => 'valueA',
                'item2' => 123,
                'isActive' => true
            ]
        ]);
    }
}
