<?php

namespace App\Traits;

use Symfony\Component\HttpFoundation\Response;
use Exception;
use App\Helpers\DatatableForResource;

trait JsonResponse
{
    public function successResponse($data, $message = "Operation Successful", $statusCode = Response::HTTP_OK)
    {
        $response = [
            "success" => true,
            "data" => $data,
            "message" => $message
        ];
        return response()->json($response, $statusCode);
    }

    public function errorResponse($data = null, $message = null, $statusCode = Response::HTTP_BAD_REQUEST)
    {
        $response = [
            "success" => false,
            "message" => $message,
            "data" => $data
        ];
        return response()->json($response, $statusCode);
    }

    public function fatalErrorResponse(Exception $e, $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        $line = $e->getTrace();

        $error = [
            "message" => $e->getMessage(),
            "trace" => $line[0],
            "mini_trace" => $line[1]
        ];

        if (strtoupper(config("APP_ENV")) === "PRODUCTION") {
            $error = null;
        }

        $response = [
            "success" => false,
            "message" => "Oops! Something went wrong on the server",
            "error" => $error
        ];
        return response()->json($response, $statusCode);
    }
}
