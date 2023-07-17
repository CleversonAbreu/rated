<?php

namespace App\Exceptions;

use App\Api\ApiMessages;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenBlacklistedException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

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
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Exception
     */
    public function render($request, Exception $exception)
    {
    
        //handle jwt errors
        if ($exception instanceof UnauthorizedHttpException) {
           
            if ($exception->getPrevious() instanceof TokenExpiredException) {
                return response()->json(
                    ApiMessages::getErrorMessage(config('constants.jwt.expired')), $exception->getStatusCode());
                
            } elseif ($exception->getPrevious() instanceof TokenInvalidException) {
                return response()->json(
                    ApiMessages::getErrorMessage(config('constants.jwt.invalid')), $exception->getStatusCode());

            } elseif ($exception->getPrevious() instanceof TokenBlacklistedException) {
                return response()->json(

                    ApiMessages::getErrorMessage(config('constants.jwt.blocked')), $exception->getStatusCode());

            } else {
                return response()->json(
                    ApiMessages::getErrorMessage(config('constants.jwt.unauthorized')), 401);

            }
        }
       
        if ($exception instanceof NotFoundHttpException) {
            return response()->json(
                ApiMessages::getErrorMessage(config('constants.routes.not_found')), 404);
        }

        return parent::render($request, $exception);
    }
    
}
