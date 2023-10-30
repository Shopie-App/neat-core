<?php

declare(strict_types=1);

use Neat\Contexts\HttpContext;
use Neat\Http\Request;
use Neat\Http\Response;
use PHPUnit\Framework\TestCase;

final class HttpContextTest extends TestCase
{
    public function testHttpContext(): void
    {
        // init injected services
        $httpReq = new Request();
        $httpRes = new Response();

        // init http context
        $httpContext = new HttpContext($httpReq, $httpRes);

        // assert
        $this->assertInstanceOf(HttpContext::class, $httpContext);
        $this->assertInstanceOf(Request::class, $httpContext->request());
        $this->assertInstanceOf(Response::class, $httpContext->response());
    }
}