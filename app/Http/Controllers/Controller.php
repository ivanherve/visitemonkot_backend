<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Enable to all controllers to return json responses to the client.
     * @param $status
     * @param $response
     * @param $httpCode
     * @return mixed
     */
    public function jsonRes($status, $response, $httpCode)
    {
        return response()->json(['status' => $status, 'response' => $response], $httpCode);
    }

    public function successRes($response)
    {
        return response()->json(['status' => 'success', 'response' => $response], 200);
    }

    public function errorRes($response, $code)
    {
        return response()->json(['status' => 'error', 'response' => $response], $code);
    }
}
