<?php

use App\Http\Middleware\FormData;
use App\Http\Middleware\ServicesInteractionMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        apiPrefix: '',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(
            [
                FormData::class,
                ServicesInteractionMiddleware::class,
                // TODO: добавить сервисы гостеха
//                AuditMiddleware::class,
            ]
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $exception) {
            $isValidationException = $exception instanceof ValidationException;
            $statusCode = $isValidationException
                ? 422
                : (method_exists($exception, 'getStatusCode')
                    ? $exception->getStatusCode()
                    : 500
                );
            $data = [
                'name' => $exception->name ?? (new ReflectionClass($exception))->getShortName(),
                'code' => $exception->getCode(),
                'data' => $isValidationException
                    ? $exception->errors()
                    : (method_exists($exception, 'getData')
                        ? $exception->getData()
                        : []
                    ),
            ];

            $isServicesInteraction = request()->isServicesInteraction;
            $isDetailed = env('EXCEPTION_DETAILED', false);

            if ($isDetailed || $isServicesInteraction) {
                $data['class'] = get_class($exception);

                if (!$isValidationException) {
                    $data = [
                        ...$data,
                        'file' => $exception->getFile(),
                        'line' => $exception->getLine(),
                        'trace' => $exception->getTrace(),
                    ];
                }
            }

            return response()->json(
                [
                    'success' => false,
                    'status' => $statusCode,
                    'message' => $statusCode >= 500 && !$isDetailed && !$isServicesInteraction
                        ? 'Внутренняя ошибка сервера'
                        : $exception->getMessage(),
                    'error' => $data
                ],
                status: $statusCode,
                options: JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
            );
        });
    })->create();
