<?php

namespace App\Traits;


trait ApiResponse
{
    public function withSuccess($data, $message = null)
    {
        return [
            'status' => 'success',
            'data' => $data,
        ];
    }
    public function withError($error){
        return [
            'status' => 'error',
            'message' => $error,
        ];
    }

    public function jsonError($error, $code = 500)
    {
        return response()->json([
            'status' => 'error',
            'message' => $error,
        ],$code);
    }
    public function jsonSuccess($data, $message = null, $statusCode = 200)
    {
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ],$statusCode);
    }

}

