<?php

declare(strict_types=1);

use Neat\Contexts\HttpContext;
use Neat\Contracts\Http\ResponseInterface;
use Neat\Middleware\PoweredByMiddleware;
use PHPUnit\Framework\TestCase;

final class PoweredByMiddlewareTest extends TestCase
{
    public function testHandleAddsPoweredByHeader(): void
    {
        $response = $this->createStub(ResponseInterface::class);
        $context = $this->createStub(HttpContext::class);

        $context->method('response')->willReturn($response);

        $capturedHeaders = [];
        $response->method('withHeader')
            ->willReturnCallback(function (string $name, string $value) use (&$capturedHeaders) {
                $capturedHeaders[$name] = $value;
            });

        $next = fn (HttpContext $ctx) => $response;

        $middleware = new PoweredByMiddleware();
        $result = $middleware->handle($context, $next);

        $this->assertSame($response, $result);

        $this->assertArrayHasKey('X-Powered-By', $capturedHeaders);
        $this->assertSame('Neat/1.0', $capturedHeaders['X-Powered-By']);
    }
}