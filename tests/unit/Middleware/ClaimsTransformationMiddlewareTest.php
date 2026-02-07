<?php

declare(strict_types=1);

use Neat\Auth\JwtTokenParser;
use Neat\Contexts\HttpContext;
use Neat\Contracts\Http\ResponseInterface;
use Neat\Contracts\Security\ClaimsPrincipalInterface;
use Neat\Middleware\ClaimsTransformationMiddleware;
use Neat\Tests\Doubles\FakeRequest;
use PHPUnit\Framework\TestCase;

final class ClaimsTransformationMiddlewareTest extends TestCase
{
    public function testHandleHydratesPrincipalWithClaimsForValidBearerToken(): void
    {
        $parser = new JwtTokenParser();
        $principal = $this->createMock(ClaimsPrincipalInterface::class);
        $context = $this->createStub(HttpContext::class);
        $request = new FakeRequest();
        $response = $this->createStub(ResponseInterface::class);

        // Payload: {"user_id":123} -> Base64Url: eyJ1c2VyX2lkIjoxMjN9
        $token = 'header.eyJ1c2VyX2lkIjoxMjN9.signature';
        $claims = ['user_id' => 123];

        $request->withHeader('Authorization', 'Bearer ' . $token);
        $context->method('request')->willReturn($request);

        $principal->expects($this->atLeastOnce())
            ->method('setClaims')
            ->with($claims);

        $next = fn (HttpContext $ctx) => $response;

        $middleware = new ClaimsTransformationMiddleware($parser, $principal);
        $result = $middleware->handle($context, $next);

        $this->assertSame($response, $result);
    }

    public function testHandleHydratesPrincipalWithEmptyClaimsWhenHeaderIsMissing(): void
    {
        $parser = new JwtTokenParser();
        $principal = $this->createMock(ClaimsPrincipalInterface::class);
        $context = $this->createStub(HttpContext::class);
        $request = new FakeRequest();
        $response = $this->createStub(ResponseInterface::class);

        $context->method('request')->willReturn($request);

        $principal->expects($this->atLeastOnce())
            ->method('setClaims')
            ->with([]);

        $next = fn (HttpContext $ctx) => $response;

        $middleware = new ClaimsTransformationMiddleware($parser, $principal);
        $result = $middleware->handle($context, $next);

        $this->assertSame($response, $result);
    }

    public function testHandleHydratesPrincipalWithEmptyClaimsWhenSchemeIsNotBearer(): void
    {
        $parser = new JwtTokenParser();
        $principal = $this->createMock(ClaimsPrincipalInterface::class);
        $context = $this->createStub(HttpContext::class);
        $request = new FakeRequest();
        $response = $this->createStub(ResponseInterface::class);

        $request->withHeader('Authorization', 'Basic user:pass');
        $context->method('request')->willReturn($request);

        $principal->expects($this->atLeastOnce())
            ->method('setClaims')
            ->with([]);

        $next = fn (HttpContext $ctx) => $response;

        $middleware = new ClaimsTransformationMiddleware($parser, $principal);
        $result = $middleware->handle($context, $next);

        $this->assertSame($response, $result);
    }
}