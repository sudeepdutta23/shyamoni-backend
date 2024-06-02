<?php
namespace App\Http\Utils;

class ResponseHandler {

    public static function sendErrorResponse($data = "Something Went Wrong! Try Again Later", $statusCode = 500) {
        return response()->json([
            "error" => true,
            "data" => $data,
        ])->setStatusCode($statusCode);
    }


    public static function sendSuccessResponse($data, $statusCode = 200) {
        return response()->json([
            "error" => false,
            "data" => $data,
        ])->setStatusCode($statusCode);
    }

   

}
