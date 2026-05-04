<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 *
 */
class BadActionHttpException extends HttpException
{
    public function __construct(
        string $message = 'Действие не определено',
        ?\Throwable $previous = null,
        array $headers = [],
        int $code = 0,
    ) {
        parent::__construct(Response::HTTP_I_AM_A_TEAPOT, $message, $previous, $headers, $code);
    }
}
