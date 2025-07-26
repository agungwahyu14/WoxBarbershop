<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;
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
            // Custom logging for different exception types
            $this->logException($e);
        });

        $this->renderable(function (Throwable $e, Request $request) {
            return $this->handleApiException($e, $request);
        });
    }

    /**
     * Custom exception logging
     */
    private function logException(Throwable $exception): void
    {
        $context = [
            'exception' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
            'url' => request()->fullUrl(),
            'method' => request()->method(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'user_id' => auth()->id(),
        ];

        // Log different types of exceptions with appropriate levels
        if ($exception instanceof \Illuminate\Database\QueryException) {
            Log::error('Database query exception', $context);
        } elseif ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            Log::warning('Authentication exception', $context);
        } elseif ($exception instanceof \Illuminate\Authorization\AuthorizationException) {
            Log::warning('Authorization exception', $context);
        } elseif ($exception instanceof \Illuminate\Validation\ValidationException) {
            Log::info('Validation exception', $context);
        } elseif ($exception instanceof HttpException) {
            $level = $exception->getStatusCode() >= 500 ? 'error' : 'warning';
            Log::log($level, 'HTTP exception', array_merge($context, [
                'status_code' => $exception->getStatusCode()
            ]));
        } else {
            Log::error('Unhandled exception', $context);
        }
    }

    /**
     * Handle API exceptions
     */
    private function handleApiException(Throwable $exception, Request $request): ?Response
    {
        if ($request->expectsJson()) {
            return $this->renderJsonException($exception);
        }

        return null;
    }

    /**
     * Render exception as JSON response
     */
    private function renderJsonException(Throwable $exception): Response
    {
        $status = 500;
        $message = 'Internal Server Error';
        $errors = [];

        if ($exception instanceof HttpException) {
            $status = $exception->getStatusCode();
            $message = $exception->getMessage() ?: Response::$statusTexts[$status];
        } elseif ($exception instanceof \Illuminate\Validation\ValidationException) {
            $status = 422;
            $message = 'Validation Error';
            $errors = $exception->errors();
        } elseif ($exception instanceof \Illuminate\Auth\AuthenticationException) {
            $status = 401;
            $message = 'Unauthenticated';
        } elseif ($exception instanceof \Illuminate\Authorization\AuthorizationException) {
            $status = 403;
            $message = 'Forbidden';
        } elseif ($exception instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
            $status = 404;
            $message = 'Resource Not Found';
        }

        $response = [
            'success' => false,
            'message' => $message,
            'status_code' => $status,
        ];

        if (!empty($errors)) {
            $response['errors'] = $errors;
        }

        // Add debug information in development
        if (config('app.debug')) {
            $response['debug'] = [
                'exception' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'trace' => explode("\n", $exception->getTraceAsString()),
            ];
        }

        return response()->json($response, $status);
    }

    /**
     * Custom error pages for web requests
     */
    public function render($request, Throwable $exception)
    {
        // Custom error pages for web requests
        if ($exception instanceof HttpException && !$request->expectsJson()) {
            $status = $exception->getStatusCode();
            
            if (view()->exists("errors.{$status}")) {
                return response()->view("errors.{$status}", [], $status);
            }
        }

        return parent::render($request, $exception);
    }
}