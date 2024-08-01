<?php

namespace App\Services\Api;


class ApiResponseService
{

    /**
     * @param  string  $message
     * @param  null  $headerCodeError - status code that will be return to front-end
     * @param  null  $errorCode - a hotspotty unique code
     * @return array
     */
    public function errorResponse(string $message, $error = null, $headerCodeError = null){

        $headerCode = $headerCodeError ?? 400;
        $content = [
            'status' => false,
            'message' => $message,
            'error' => $error
        ];

        return response()->json([
            'headerCode' => $headerCode,
            'content' => $content
        ], $headerCode);
    }

    /**
     * @param  string  $message
     * @param  array  $data
     * @param  int  $headerCode
     * @return array
     */
    public function successResponse(string $message, array $data, int $headerCode = 200){

        $content = [
            'status' => true,
            'message' => $message,
            'data' => $data
        ];

        return response()->json([
            'headerCode' => $headerCode,
            'content' => $content
        ], $headerCode);

    }

}
