<?php

namespace App;

trait ResponseJson
{
    public function sendSuccess($message, $status, array $data  = [])
    {
      return response()->json([
          "message"=> $message,
          "data"=> $data,
          "status"=> $status,
        ], $status, [], JSON_UNESCAPED_SLASHES);
    }
    public function sendError($message, $status, array $errors = [], array $data = [])
    {
      return response()->json([
          "message"=> $message,
          "data"=> $data,
          "status"=> $status,
          "errors"=> $errors,
        ], $status, [], JSON_UNESCAPED_SLASHES);
    }
}
