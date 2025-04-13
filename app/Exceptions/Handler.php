<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use BadMethodCallException;
use ReflectionClass;
use Illuminate\Support\Str;
use Illuminate\View\ViewException;
use InvalidArgumentException;

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
            //
        });
        
        // Catch "View not found" errors
        $this->renderable(function (InvalidArgumentException $e, $request) {
            if (Str::contains($e->getMessage(), 'View [') && Str::contains($e->getMessage(), '] not found')) {
                return response()->view('errors.view-not-found', ['exception' => $e], 404);
            }
        });
        
        // Also catch view exceptions that might be thrown in production
        $this->renderable(function (ViewException $e, $request) {
            if (Str::contains($e->getMessage(), 'View [') && Str::contains($e->getMessage(), '] not found')) {
                return response()->view('errors.view-not-found', ['exception' => $e], 404);
            }
        });
        
        $this->renderable(function (BadMethodCallException $e, $request) {
            if (Str::contains($e->getMessage(), 'Call to undefined method')) {
                return response()->view('errors.method-not-found', ['exception' => $e], 500);
            }
        });
        
        $this->renderable(function (Throwable $e, $request) {
            // Handle method not found errors
            if ($this->isMethodNotFound($e) && !config('app.debug')) {
                return response()->view('errors.method-not-found', ['exception' => $e], 500);
            }
            
            // Handle other 500 errors
            if ($this->isHttpException($e) && $e->getStatusCode() == 500 && !config('app.debug')) {
                return response()->view('errors.500', ['exception' => $e], 500);
            }
            
            // Handle 404 errors
            if ($this->isHttpException($e) && $e->getStatusCode() == 404) {
                return response()->view('errors.404', ['exception' => $e], 404);
            }
        });
    }
    
    /**
     * Determine if the exception is a method not found error.
     *
     * @param  \Throwable  $e
     * @return bool
     */
    protected function isMethodNotFound(Throwable $e): bool
    {
        if ($e instanceof BadMethodCallException) {
            return true;
        }
        
        return Str::contains($e->getMessage(), [
            'Call to undefined method',
            'Method not found',
            'has no method',
            'Undefined method'
        ]);
    }
}