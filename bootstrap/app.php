<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\UniqueConstraintViolationException;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        // web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'verified' => \App\Http\Middleware\EnsureEmailIsVerified::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->shouldRenderJsonWhen(function (Request $request, Throwable $e) {
            if ($request->is('api/*')) {
                return true;
            }
            return $request->expectsJson();
        });

        
        $exceptions->render(function(UniqueConstraintViolationException $e,Request $request){
            if($request->expectsJson()){
                if($e instanceof UniqueConstraintViolationException){
                    return response()->json([
                        'success' => false,
                        'message' => $e->getMessage(),
                        'status'  => 422,
                        'errors'  => 'UniqueConstraintViolationException',
                    ], 422);
                }
            }
        });

        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->expectsJson()) {                
                if ($e instanceof ValidationException) {
                    return response()->json([
                        'success' => false,
                        'message' => $e->getMessage(),
                        'status'  => 422,
                        'errors'  => $e->errors(),
                    ], 422);
                }
            }
        });

        if(! config('app.debug')){
            $exceptions->render(function (Throwable $e, Request $request) {
                return response()->json([
                    'success' => false,
                    'message' => 'Server Error: ' . $e->getMessage(),
                    'status'  => 500,
                    'errors'  => [],
                ], 500);
            });
        }
    })->create();

return $app;
