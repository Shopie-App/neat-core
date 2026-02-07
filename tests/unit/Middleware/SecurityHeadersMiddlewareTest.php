<?php

declare(strict_types=1);

use Neat\Contexts\HttpContext;
use Neat\Contracts\Http\ResponseInterface;
use Neat\Middleware\SecurityHeadersMiddleware;
use PHPUnit\Framework\TestCase;

final class SecurityHeadersMiddlewareTest extends TestCase
{
    public function testHandleAddsSecurityHeaders(): void
    {
        $response = $this->createStub(ResponseInterface::class);
        $context = $this->createStub(HttpContext::class);

        $context->method('response')->willReturn($response);

        $capturedHeaders = [];
        $response->method('withHeader')
            ->willReturnCallback(function (string $name, string $value) use (&$capturedHeaders, $response) {
                $capturedHeaders[$name] = $value;
                return $response;
            });

        $next = fn (HttpContext $ctx) => $response;

        $middleware = new SecurityHeadersMiddleware();
        $result = $middleware->handle($context, $next);

        $this->assertSame($response, $result);

        $expectedHeaders = [
            'Cache-Control' => 'no-store, max-age=0',
            'Content-Security-Policy' => 'default-src \'none\'; frame-ancestors \'none\'; sandbox',
            'Strict-Transport-Security' => 'max-age=63072000; includeSubDomains; preload',
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
        ];

        foreach ($expectedHeaders as $name => $value) {
            $this->assertArrayHasKey($name, $capturedHeaders, "Header '$name' was not set.");
            $this->assertSame($value, $capturedHeaders[$name], "Header '$name' value mismatch.");
        }
    }
}