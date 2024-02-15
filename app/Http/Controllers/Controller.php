<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function success($data, $message = "okay", $statusCode = 200) 
    {
        return response()->json([
            'data' => $data,
            'success' => true,
            'message' => $message
        ], $statusCode);
    }

    public function error($message, $statusCode = 400)
    {
        return response()->json([
            'data' => null,
            'success' => false,
            'message' => $message
        ], $statusCode);
    }
}
