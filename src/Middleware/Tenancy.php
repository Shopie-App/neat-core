<?php 

declare(strict_types=1);

namespace Neat\Middleware;

use Closure;
use Neat\Contexts\HttpContext;
use Neat\Contracts\Http\MiddlewareInterface;
use Neat\Contracts\Http\ResponseInterface;

final class Tenancy implements MiddlewareInterface
{
    public function handle(HttpContext $context, Closure $next): ResponseInterface
    {
        // next middleware
        return $next($context);
    }
}