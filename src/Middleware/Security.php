<?php 

declare(strict_types=1);

namespace Neat\Middleware;

use Closure;
use Neat\Contexts\HttpContext;
use Neat\Contracts\Http\MiddlewareInterface;
use Neat\Contracts\Http\ResponseInterface;

final class Security implements MiddlewareInterface
{
    public function handle(HttpContext $context, Closure $next): ResponseInterface
    {
        $context->response()->withHeader('Cache-Control', 'no-store');

        $context->response()->withHeader('Content-Security-Policy', 'frame-ancestors \'none\'');

        $context->response()->withHeader('Strict-Transport-Security', 'max-age=31536000');

        $context->response()->withHeader('X-Content-Type-Options', 'nosniff');

        $context->response()->withHeader('X-Frame-Options', 'DENY');

        return $next($context);
    }
}