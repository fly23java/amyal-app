<?php

namespace App\Exceptions;

use App\Services\ErrorLoggingService;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            // Custom error logging
            if ($this->shouldReport($e)) {
                ErrorLoggingService::logError($e, request(), [
                    'severity' => $this->getErrorSeverity($e),
                    'reportable' => true
                ]);

                // Send notification for critical errors
                if ($this->isCriticalError($e)) {
                    ErrorLoggingService::sendErrorNotification($e, [
                        'url' => request()->fullUrl(),
                        'user_id' => auth()->id()
                    ]);
                }
            }
        });

        $this->renderable(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => 'Resource not found',
                    'message' => 'The requested resource could not be found.'
                ], 404);
            }

            return response()->view('errors.404', [], 404);
        });

        $this->renderable(function (QueryException $e, Request $request) {
            ErrorLoggingService::logDatabaseError($e, $e->getSql(), $e->getBindings());

            if ($request->is('api/*')) {
                return response()->json([
                    'error' => 'Database error',
                    'message' => 'A database error occurred. Please try again later.'
                ], 500);
            }

            if (app()->environment('production')) {
                return response()->view('errors.500', [], 500);
            }

            // In development, let Laravel handle it normally to show debug info
            return null;
        });

        $this->renderable(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => 'Validation failed',
                    'message' => 'The given data was invalid.',
                    'errors' => $e->errors()
                ], 422);
            }

            // Let Laravel handle it normally for web requests
            return null;
        });

        $this->renderable(function (AuthenticationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => 'Unauthenticated',
                    'message' => 'Authentication is required to access this resource.'
                ], 401);
            }

            return redirect()->guest(route('login'));
        });

        $this->renderable(function (HttpException $e, Request $request) {
            $statusCode = $e->getStatusCode();

            if ($request->is('api/*')) {
                return response()->json([
                    'error' => 'HTTP Error',
                    'message' => $e->getMessage() ?: 'An HTTP error occurred.',
                    'status_code' => $statusCode
                ], $statusCode);
            }

            // Handle common HTTP errors
            if (view()->exists("errors.{$statusCode}")) {
                return response()->view("errors.{$statusCode}", [], $statusCode);
            }

            return response()->view('errors.generic', [
                'statusCode' => $statusCode,
                'message' => $e->getMessage()
            ], $statusCode);
        });
    }

    /**
     * Determine the severity of an error
     *
     * @param Throwable $e
     * @return string
     */
    private function getErrorSeverity(Throwable $e): string
    {
        if ($e instanceof QueryException) {
            return 'high';
        }

        if ($e instanceof \Error || $e instanceof \ErrorException) {
            return 'critical';
        }

        if ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
            if ($statusCode >= 500) {
                return 'high';
            } elseif ($statusCode >= 400) {
                return 'medium';
            }
        }

        return 'low';
    }

    /**
     * Determine if an error is critical
     *
     * @param Throwable $e
     * @return bool
     */
    private function isCriticalError(Throwable $e): bool
    {
        // Critical errors that require immediate attention
        return $e instanceof \Error ||
               $e instanceof \ErrorException ||
               ($e instanceof QueryException && str_contains($e->getMessage(), 'Connection refused')) ||
               ($e instanceof HttpException && $e->getStatusCode() >= 500);
    }

    /**
     * Convert an authentication exception into a response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Unauthenticated',
                'message' => 'Authentication is required to access this resource.'
            ], 401);
        }

        return redirect()->guest($exception->redirectTo() ?? route('login'));
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param  \Illuminate\Validation\ValidationException  $e
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $e, $request)
    {
        if ($e->response) {
            return $e->response;
        }

        if ($request->expectsJson()) {
            return response()->json([
                'error' => 'Validation failed',
                'message' => 'The given data was invalid.',
                'errors' => $e->errors()
            ], 422);
        }

        return redirect($e->redirectTo ?? url()->previous())
                    ->withInput($request->input())
                    ->withErrors($e->errors(), $e->errorBag);
    }
}