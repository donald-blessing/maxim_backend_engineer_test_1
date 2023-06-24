<?php

/**
 * Aladdin Digital Bank
 *
 * Aladdin is the World's first digital open bank, seamlessly combining banking and commerce.
 *
 * @category Helpers
 * @author  Aladdin Developer Team
 * @copyright Copyright (c) 2021. All right reserved
 * @version 1.1.0
 */

namespace App\Helpers;

use Illuminate\Support\Facades\DB;
use App\Libraries\Adb\Src\Functions as AdbFunc;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

/**
 * OTP Generator class
 *
 * This class contains functions to generate one time password
 *
 */

class ResponseHelper
{
    public static function skeleton()
    {
        return (object) [
            "status" => false,
            "data" => (object) [],
            "debug" => (object) []
        ];
    }

    public static function format($config = null, $status = false)
    {
        $config = ($config) ? (object) $config : (object) [];
        $data = (object) [
            "status" => (isset($config->status)) ? $config->status : $status,
            "data" => (isset($config->data) && !empty($config->data)) ? $config->data : null,
            "response" => (isset($config->msg)) ? $config->msg : __('custom.default_response'),
            "code" => (isset($config->code)) ? $config->code : 200,
            "debug" => (isset($config->debug) && !empty($config->debug)) ? $config->debug : null
        ];

        return (object) [
            "status"    => $data->status,
            "meta_data" => (object) [
                "data" => $data->data,
                "response" => $data->response,
                "status_code" => $data->code
            ],
            "debug" => $data->debug,
        ];
    }

    public static function formatted_response($config = null){
        $formatted =  self::format($config);
        return response()->json($formatted, $formatted->meta_data->status_code);
    }

    public static function jsonResponse($msg, int $code = 400, $data = [], $status = false) {
        $response = self::format([
            "msg"=> $msg, "code"=> $code, "data"=> $data
        ], $status);

        return response()->json($response, $code);
    }

    public static function default_failure_response($msg = false)
    {
        return (object) [
            "status" => false,
            "code" => 500,
            "msg" => ($msg) ? $msg : __('custom.default')
        ];
    }

    public static function method_error_response($msg = false)
    {
        return (object) [
            "status" => false,
            "code" => 405,
            "msg" => ($msg) ? $msg : __('custom.invalid_request_method')
        ];
    }

    public static function value_error_response($msg = false)
    {
        return (object) [
            "status" => false,
            "code" => 403,
            "msg" => ($msg) ? $msg : __('custom.invalid_values')
        ];
    }

    public static function unauthorized_response($msg = false)
    {
        return (object) [
            "status" => false,
            "code" => 401,
            "msg" => ($msg) ? $msg : __('custom.unauthorized')
        ];
    }

    public static function bad_request_error_response($msg = false, $debug = null)
    {
        return (object) [
            "status" => false,
            "code" => 400,
            "msg" => ($msg) ? $msg : __('custom.bad_request'),
            "debug" => $debug
        ];
    }

    public static function success_response($msg = false, $data = null)
    {
        return (object) [
            "status" => true,
            "code" => 200,
            "data" => $data,
            "msg" => ($msg) ? $msg : "Request Successful"
        ];
    }

    public static function token_response($token, $data)
    {
        return response()->json([
            'access_token' => $token,
            'data' => $data,
        ], 200);
    }

    public static function serverError($error, int $code = 500)
    {
        return response()->json([
            "status" => false,
            "meta_data" => [
                "data" => null,
                "status_code" => $code,
                "response" => "Oops, something went wrong. Please try again later",
            ],
            "debug" => env('APP_DEBUG') ? $error->getMessage() : null
        ], $code);
    }

    public static function clientError($response, int $code = 400)
    {
        return response()->json([
            "status" => false,
            "meta_data" => [
                "data" => null,
                "status_code" => $code,
                "response" => $response
            ],
            "debug" => null
        ], $code);
    }
}
