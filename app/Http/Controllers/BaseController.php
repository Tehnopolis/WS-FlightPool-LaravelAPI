<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;

class BaseController extends Controller
{
    protected function sendWithCode($json, int $code) {
        return response()->json($json, $code);
    }

    public function sendResponse($result, int $code = 200)
    {
        $response = [];

        if(isset($result)) { $response['data'] = $result; }
        else { $response = null; }

        return $this->sendWithCode($response, $code);
    }

    public function sendError($message, $errorMessages = [], int $code = 404)
    {
        $response = [
            'error' => [
                'code' => $code,
                'message' => $message
            ]
        ];

        if(!empty($errorMessages)){
            $response['error']['errors'] = $errorMessages;
        }

        return $this->sendWithCode($response, $code);
    }
}