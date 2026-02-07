<?php 

declare(strict_types=1);

namespace Neat\Middleware;

use Closure;
use Neat\Contexts\HttpContext;
use Neat\Contracts\Http\MiddlewareInterface;
use Neat\Contracts\Http\ResponseInterface;

final class SecurityHeadersMiddleware implements MiddlewareInterface
{
    public function handle(HttpContext $context, Closure $next): ResponseInterface
    {
        $context->response()->withHeader('Cache-Control', 'no-store, max-age=0');

        $context->response()->withHeader('Content-Security-Policy', 'default-src \'none\'; frame-ancestors \'none\'; sandbox');

        $context->response()->withHeader('Strict-Transport-Security', 'max-age=63072000; includeSubDomains; preload');

        $context->response()->withHeader('X-Content-Type-Options', 'nosniff');

        $context->response()->withHeader('X-Frame-Options', 'DENY');

        return $next($context);
    }
}