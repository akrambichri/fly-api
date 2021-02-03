<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Facades\App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

use  Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\JWTException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (ValidationException $e) {
            return ApiController::setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
                ->respondWithError(
                    message: 'validation_error_occured',
                    inlineData: ['errors' => $e->errors()]
                );
        });

        $this->renderable(function (AuthenticationException $e) {
            return ApiController::setStatusCode(Response::HTTP_UNAUTHORIZED)
                ->respondWithError(
                    message: 'unauthenticated'
                );
        });

        $this->renderable(function (AuthorizationException $e) {
            return ApiController::setStatusCode(Response::HTTP_FORBIDDEN)
                ->respondWithError(
                    message: 'action_unauthorized'
                );
        });

        $this->renderable(function (AccessDeniedHttpException $e) {
            return ApiController::setStatusCode(Response::HTTP_FORBIDDEN)
                ->respondWithError(
                    message: 'action_unauthorized'
                );
        });

        $this->renderable(function (UnauthorizedHttpException $e) {
            return ApiController::setStatusCode(Response::HTTP_UNAUTHORIZED)
                ->respondWithError(
                    message: 'action_unauthorized'
                );
        });

        $this->renderable(function (NotFoundHttpException $e) {
            return ApiController::setStatusCode(Response::HTTP_NOT_FOUND)
                ->respondWithError(
                    message: 'not_found'
                );
        });

        $this->renderable(function (ThrottleRequestsException $e) {
            return ApiController::setStatusCode(Response::HTTP_TOO_MANY_REQUESTS)
                ->respondWithError(
                    message: 'too_many_requests'
                );
        });

        $this->renderable(function (MethodNotAllowedHttpException $e) {
            return ApiController::setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED)
                ->respondWithError(
                    message: 'method_not_allowed'
                );
        });

        $this->renderable(function (HttpException $e) {
            if ($e->getStatusCode() === Response::HTTP_INTERNAL_SERVER_ERROR) {
                return ApiController::setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR)
                    ->respondWithError(
                        message: 'internal_server_error'
                    );
            }

            return ApiController::setStatusCode($e->getStatusCode())
                ->respondWithError(
                    message: $e->getMessage()
                );
        });

        $this->renderable(function (TokenExpiredException $e) {
            return ApiController::setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED)
                ->respondWithError(
                    message: 'token_expired'
                );
        });

        $this->renderable(function (TokenInvalidException $e) {
            return ApiController::setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED)
                ->respondWithError(
                    message: 'token_invalid'
                );
        });

        $this->renderable(function (JWTException $e) {
            return ApiController::setStatusCode(Response::HTTP_METHOD_NOT_ALLOWED)
                ->respondWithError(
                    message: 'token_absent'
                );
        });

        $this->reportable(function (Throwable $e) {
        });
    }
}
