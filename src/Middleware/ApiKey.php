<?php

declare(strict_types=1);

namespace Neat\Middleware;

use Closure;
use Neat\Contexts\HttpContext;
use Neat\Contracts\Authentication\AuthenticationInterface;
use Neat\Contracts\Http\MiddlewareInterface;
use Neat\Contracts\Http\ResponseInterface;

final class ApiKey implements MiddlewareInterface
{
    public function __construct(private AuthenticationInterface $auth)
    {
    }

    public function handle(HttpContext $context, Closure $next): ResponseInterface
    {
        // authenticate with Api key
        $this->auth->authenticate();

        // next middleware
        return $next($context);
    }
}