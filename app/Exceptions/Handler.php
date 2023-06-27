<?php

namespace App\Exceptions;

use App\Helpers\ResponseHelper as ApiResponse;
use Error;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use PHPUnit\Framework\MockObject\BadMethodCallException;
use Symfony\Component\HttpFoundation\Response as HttpCode;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            return ApiResponse::serverError($e);
        });

        $this->reportable(function (Error $e) {
            return ApiResponse::serverError($e);
        });

        $this->reportable(function (MethodNotAllowedHttpException $e) {
            return ApiResponse::serverError($e, HttpCode::HTTP_METHOD_NOT_ALLOWED);
        });

        $this->reportable(function (QueryException $e) {
            return ApiResponse::serverError($e);
        });

        $this->reportable(function (NotFoundHttpException $e) {
            $response = "The requested route doesn't exist on this resource";
            return ApiResponse::clientError($response, HttpCode::HTTP_NOT_FOUND);
        });

        $this->reportable(function (BadMethodCallException $error) {
            return ApiResponse::serverError($error);
        });
    }
}
