<?php

declare(strict_types=1);

namespace Neat\Middleware;

use Closure;
use Neat\Contexts\HttpContext;
use Neat\Contracts\Http\MiddlewareInterface;
use Neat\Contracts\Http\ResponseInterface;
use Neat\Contracts\Security\ClaimsPrincipalInterface;

final class AuthorizationMiddleware implements MiddlewareInterface
{
    public function __construct(private ClaimsPrincipalInterface $user) {}

    public function handle(HttpContext $context, Closure $next): ResponseInterface
    {
        /**
         * [AllowAnonymous] wins on a method if [Authorize] is in class
         */

        // next middleware
        return $next($context);
    }
}