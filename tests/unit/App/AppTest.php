<?php

declare(strict_types=1);

use Neat\App\App;
use Neat\Contexts\AppContext;
use Neat\Contexts\HttpContext;
use Neat\Contracts\Http\MiddlewareChainInterface;
use Neat\Contracts\Http\RequestInterface;
use Neat\Contracts\Http\ResponseInterface;
use PHPUnit\Framework\TestCase;
use Shopie\DiContainer\Contracts\ServiceContainerInterface;
use Shopie\DiContainer\Contracts\ServiceProviderInterface;
use Neat\Tests\Doubles\FakeRequest;

final class AppTest extends TestCase
{
    public function testAppConstructor(): void
    {
        // init app
        $app = new App();

        // assert
        $this->assertInstanceOf(App::class, $app);
    }

    public function testRunResolvesContextFromContainerWhenNullPassed(): void
    {
        $app = new App();

        // Mocks
        $appContext = $this->createStub(AppContext::class);
        $serviceProvider = $this->createMock(ServiceProviderInterface::class);
        $serviceContainer = $this->createMock(ServiceContainerInterface::class);
        $httpContext = $this->createStub(HttpContext::class);
        $response = $this->createMock(ResponseInterface::class);
        $middlewareChain = $this->createMock(MiddlewareChainInterface::class);

        // Setup AppContext
        $appContext->method('provider')->willReturn($serviceProvider);
        $appContext->method('service')->willReturn($serviceContainer);
        $app->setAppContext($appContext);
        $app->setMiddlewareChain($middlewareChain);

        // Expectations
        // 1. Resolve HttpContext from provider (Standard FPM behavior)
        $serviceProvider->expects($this->once())
            ->method('getService')
            ->with(HttpContext::class)
            ->willReturn($httpContext);

        // 2. Middleware processing
        $middlewareChain->expects($this->once())
            ->method('process')
            ->with($httpContext);

        // 3. Output
        $httpContext->method('response')->willReturn($response);
        $response->expects($this->once())->method('output');

        // 4. Reset state
        $serviceContainer->expects($this->once())->method('resetAll');

        // Run
        $app->run();
    }

    public function testRunUsesPassedContextAndSyncsContainer(): void
    {
        $app = new App();

        // Mocks
        $appContext = $this->createStub(AppContext::class);
        $serviceContainer = $this->createMock(ServiceContainerInterface::class);
        $httpContext = $this->createStub(HttpContext::class);
        $request = new FakeRequest();
        $response = $this->createMock(ResponseInterface::class);
        $middlewareChain = $this->createMock(MiddlewareChainInterface::class);

        $httpContext->method('request')->willReturn($request);
        $httpContext->method('response')->willReturn($response);

        // Setup AppContext
        $appContext->method('service')->willReturn($serviceContainer);
        $app->setAppContext($appContext);
        $app->setMiddlewareChain($middlewareChain);

        // Expectations
        // 1. setObject calls (Worker behavior: Sync container with passed context)
        $serviceContainer->expects($this->exactly(3))
            ->method('setObject')
            ->willReturnCallback(function (string $class, object $instance) use ($httpContext, $request, $response) {
                if ($class === HttpContext::class) {
                    $this->assertSame($httpContext, $instance);
                } elseif ($class === RequestInterface::class) {
                    $this->assertSame($request, $instance);
                } elseif ($class === ResponseInterface::class) {
                    $this->assertSame($response, $instance);
                }
            });

        // 2. Middleware processing
        $middlewareChain->expects($this->once())
            ->method('process')
            ->with($httpContext);

        // 3. Output
        $response->expects($this->once())->method('output');

        // 4. Reset state
        $serviceContainer->expects($this->once())->method('resetAll');

        // Run
        $app->run($httpContext);
    }
}