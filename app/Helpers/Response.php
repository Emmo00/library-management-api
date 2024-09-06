<?php

if (!function_exists("respondSuccess")) {
    function respondSuccess($message, $data = null, $status = 200, array $headers = [])
    {
        return response()->json(
            [
                "status" => true,
                "message" => $message,
                "data" => empty($data) ? null : $data,
            ],
            $status,
            $headers
        );
    }
}

if (!function_exists("respondError")) {
    function respondError($message, $status = 500, $data = null, array $headers = [])
    {
        return response()->json(
            [
                "status" => false,
                "message" => $message,
                "data" => empty($data) ? null : $data,
            ],
            $status,
            $headers
        );
    }
}
