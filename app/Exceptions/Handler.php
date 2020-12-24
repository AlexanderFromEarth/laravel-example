<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->expectsJson()) 
            {
                return response()->json([
                        "success"   => false,
                        "message"   => "Not Found"   
                    ], 404);
            }
            else
            {
                abort(404);
            }
        });
        $this->renderable(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->expectsJson()) 
            {
                return response()->json([
                        "success"   => false,
                        "message"   => "Method Not Allowed"   
                    ], 400);
            }
            else
            {
                abort(400);
            }
        });
        $this->renderable(function (AccessDeniedHttpException $e, Request $request) {
            $code = $request->isMethod("GET") ? 403 : 405;
            if ($request->expectsJson()) 
            {
                return response()->json([
                        "success"   => false,
                        "message"   => "Unathorized"   
                    ], $code);
            }
            else
            {
                abort($code);
            }
        });
        $this->renderable(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson()) 
            {
                return response()->json([
                        "success"   => false,
                        "message"   => "Unauthenticated"   
                    ], 401);
            }
            else
            {
                abort(401);
            }
        });
        $this->renderable(function (ValidationException $e, Request $request) {
            if ($request->expectsJson()) 
            {
                return response()->json([
                        "success"   => false,
                        "message"   => "Not Valid or Not Unique"   
                    ], 400);
            }
            else
            {
                abort(400);
            }
        });
    }
}
