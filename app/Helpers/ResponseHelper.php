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

use Illuminate\Http\JsonResponse;

/**
 * OTP Generator class
 *
 * This class contains functions to generate one time password
 *
 */
class ResponseHelper
{
    /**
     * @return object
     */
    public static function skeleton(): object
    {
        return (object)[
            "status" => false,
            "data" => (object)[],
            "debug" => (object)[]
        ];
    }

    /**
     * @param $config
     * @param bool $status
     * @return object
     */
    public static function format($config = null, bool $status = false): object
    {
        $config = ($config) ? (object)$config : (object)[];
        $data = (object)[
            "status" => $config->status ?? $status,
            "data" => (!empty($config->data)) ? $config->data : null,
            "response" => $config->msg ?? __('custom.default_response'),
            "code" => $config->code ?? 200,
            "debug" => (!empty($config->debug)) ? $config->debug : null
        ];

        return (object)[
            "status" => $data->status,
            "meta_data" => (object)[
                "data" => $data->data,
                "response" => $data->response,
                "status_code" => $data->code
            ],
            "debug" => $data->debug,
        ];
    }

    /**
     * @param $config
     * @return JsonResponse
     */
    public static function formatted_response($config = null): JsonResponse
    {
        $formatted = self::format($config);
        return response()->json($formatted, $formatted->meta_data->status_code);
    }

    /**
     * @param $msg
     * @param int $code
     * @param mixed $data
     * @param bool $status
     * @return JsonResponse
     */
    public static function jsonResponse($msg, int $code = 400, mixed $data = [], bool $status = false): JsonResponse
    {
        $response = self::format([
            "msg" => $msg, "code" => $code, "data" => $data
        ], $status);

        return response()->json($response, $code);
    }

    /**
     * @param bool $msg
     * @return object
     */
    public static function default_failure_response(bool $msg = false): object
    {
        return (object)[
            "status" => false,
            "code" => 500,
            "msg" => ($msg) ?: __('custom.default')
        ];
    }

    /**
     * @param bool $msg
     * @return object
     */
    public static function method_error_response(bool $msg = false): object
    {
        return (object)[
            "status" => false,
            "code" => 405,
            "msg" => ($msg) ?: __('custom.invalid_request_method')
        ];
    }

    /**
     * @param bool $msg
     * @return object
     */
    public static function value_error_response(bool $msg = false): object
    {
        return (object)[
            "status" => false,
            "code" => 403,
            "msg" => ($msg) ?: __('custom.invalid_values')
        ];
    }

    /**
     * @param bool $msg
     * @return object
     */
    public static function unauthorized_response(bool $msg = false): object
    {
        return (object)[
            "status" => false,
            "code" => 401,
            "msg" => ($msg) ?: __('custom.unauthorized')
        ];
    }

    /**
     * @param bool $msg
     * @param $debug
     * @return object
     */
    public static function bad_request_error_response(bool $msg = false, $debug = null): object
    {
        return (object)[
            "status" => false,
            "code" => 400,
            "msg" => ($msg) ?: __('custom.bad_request'),
            "debug" => $debug
        ];
    }

    /**
     * @param bool $msg
     * @param $data
     * @return object
     */
    public static function success_response(bool $msg = false, $data = null): object
    {
        return (object)[
            "status" => true,
            "code" => 200,
            "data" => $data,
            "msg" => ($msg) ?: "Request Successful"
        ];
    }

    /**
     * @param $token
     * @param $data
     * @return JsonResponse
     */
    public static function token_response($token, $data): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'data' => $data,
        ], 200);
    }

    /**
     * @param $error
     * @param int $code
     * @return JsonResponse
     */
    public static function serverError($error, int $code = 500): JsonResponse
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

    /**
     * @param $response
     * @param int $code
     * @return JsonResponse
     */
    public static function clientError($response, int $code = 400): JsonResponse
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
