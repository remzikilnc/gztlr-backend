<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->renderable(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have the required permissions.',
            ], 403);
        });

        $this->renderable(function (AuthenticationException $e, $request) {
            return response()->unauthorized();
        });

        $this->renderable(function (AccessDeniedHttpException $e, $request) {
            return response()->forbidden();
        });

        $this->renderable(function (AuthorizationException $e, $request) {
            return response()->forbidden();
        });

        $this->renderable(function (TokenExpiredException  $e, $request) {
            return response()->forbidden();
        });

        $this->renderable(function (NotFoundHttpException  $e, $request) {
            return response()->notFound();
        });

        $this->renderable(function (ModelNotFoundException  $e, $request) {
            return response()->notFound();
        });
/*
        $this->renderable(function (QueryException  $e, $request) {
            return response()->internalServerError('A query error occured');
        });*/
    }
}
