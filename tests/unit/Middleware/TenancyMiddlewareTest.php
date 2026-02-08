<?php

declare(strict_types=1);

use Neat\Contexts\HttpContext;
use Neat\Contracts\Http\ResponseInterface;
use Neat\Contracts\Security\ClaimsPrincipalInterface;
use Neat\Middleware\TenancyMiddleware;
use Neat\Providers\TenantProvider;
use PHPUnit\Framework\TestCase;

final class TenancyMiddlewareTest extends TestCase
{
    public function testHandleRetrievesTenantIdWhenClaimExists(): void
    {
        $principal = $this->createMock(ClaimsPrincipalInterface::class);
        $context = $this->createStub(HttpContext::class);
        $response = $this->createStub(ResponseInterface::class);
        $provider = $this->createMock(TenantProvider::class);

        $claimName = 'org_id';

        $principal->expects($this->once())->method('hasClaim')->with($claimName)->willReturn(true);
        $principal->expects($this->once())
            ->method('getClaim')
            ->with($claimName)
            ->willReturn(123);

        $provider->expects($this->once())
            ->method('set')
            ->with('123');

        $next = fn (HttpContext $ctx) => $response;

        $middleware = new TenancyMiddleware($principal, $provider, $claimName);
        $result = $middleware->handle($context, $next);

        $this->assertSame($response, $result);
    }

    public function testHandleIgnoresTenantIdWhenClaimIsMissing(): void
    {
        $principal = $this->createMock(ClaimsPrincipalInterface::class);
        $context = $this->createStub(HttpContext::class);
        $response = $this->createStub(ResponseInterface::class);
        $provider = $this->createMock(TenantProvider::class);

        $claimName = 'org_id';

        $principal->expects($this->once())->method('hasClaim')->with($claimName)->willReturn(false);
        $principal->expects($this->never())
            ->method('getClaim');

        $provider->expects($this->never())->method('set');

        $next = fn (HttpContext $ctx) => $response;

        $middleware = new TenancyMiddleware($principal, $provider, $claimName);
        $result = $middleware->handle($context, $next);

        $this->assertSame($response, $result);
    }
}