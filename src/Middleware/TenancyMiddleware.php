<?php 

declare(strict_types=1);

namespace Neat\Middleware;

use Closure;
use Neat\Contexts\HttpContext;
use Neat\Contracts\Http\MiddlewareInterface;
use Neat\Contracts\Http\ResponseInterface;
use Neat\Contracts\Security\ClaimsPrincipalInterface;

final class TenancyMiddleware implements MiddlewareInterface
{
    public function __construct(private ClaimsPrincipalInterface $user) {}

    public function handle(HttpContext $context, Closure $next): ResponseInterface
    {
        if ($this->user->hasClaim('tid')) {
            $tenantId = $this->user->getClaim('tid');
            
            // Example: Set the tenant ID on the context or configure a DB service
            // $context->setTenantId($tenantId);
        }

        // next middleware
        return $next($context);
    }
}