<?php

declare(strict_types=1);

use Neat\Contexts\HttpContext;
use Neat\Contracts\Http\ResponseInterface;
use Neat\Contracts\Security\ClaimsPrincipalInterface;
use Neat\Middleware\Tenancy;
use Neat\Middleware\TenancyMiddleware;
use PHPUnit\Framework\TestCase;

final class TenancyMiddlewareTest extends TestCase
{
    public function testHandleRetrievesTenantIdWhenClaimExists(): void
    {
        $principal = $this->createMock(ClaimsPrincipalInterface::class);
        $context = $this->createStub(HttpContext::class);
        $response = $this->createStub(ResponseInterface::class);

        $principal->method('hasClaim')->with('tid')->willReturn(true);
        $principal->expects($this->once())
            ->method('getClaim')
            ->with('tid')
            ->willReturn(123);

        $next = fn (HttpContext $ctx) => $response;

        $middleware = new TenancyMiddleware($principal);
        $result = $middleware->handle($context, $next);

        $this->assertSame($response, $result);
    }

    public function testHandleIgnoresTenantIdWhenClaimIsMissing(): void
    {
        $principal = $this->createMock(ClaimsPrincipalInterface::class);
        $context = $this->createStub(HttpContext::class);
        $response = $this->createStub(ResponseInterface::class);

        $principal->method('hasClaim')->with('tid')->willReturn(false);
        $principal->expects($this->never())
            ->method('getClaim');

        $next = fn (HttpContext $ctx) => $response;

        $middleware = new TenancyMiddleware($principal);
        $result = $middleware->handle($context, $next);

        $this->assertSame($response, $result);
    }
}