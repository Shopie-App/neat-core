<?php 

declare(strict_types=1);

namespace Neat\Middleware;

use Closure;
use Neat\Contexts\HttpContext;
use Neat\Contracts\Http\MiddlewareInterface;
use Neat\Contracts\Http\ResponseInterface;
use Neat\Contracts\Security\ClaimsPrincipalInterface;
use Neat\Providers\TenantProvider;

final class TenancyMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ClaimsPrincipalInterface $user,
        private TenantProvider $tenantProvider
    ) {}

    public function handle(HttpContext $context, Closure $next): ResponseInterface
    {
        if ($this->user->hasClaim('ten')) {

            $tenantId = (string) $this->user->getClaim('ten');
            
            $this->tenantProvider->set($tenantId);
        }

        // next middleware
        return $next($context);
    }
}