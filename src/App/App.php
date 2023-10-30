<?php

declare(strict_types=1);

namespace Neat\App;

use Neat\Contexts\AppContext;
use Neat\Contexts\HttpContext;
use Neat\Contracts\Http\MiddlewareChainInterface;

class App
{
    /**
     * Application context object.
     * @var AppContext
     */
    private AppContext $appContext;

    /**
     * Http req/res context object.
     * @var HttpContext
     */
    private ?HttpContext $httpContext = null;

    /**
     * Chain of responsibility abstraction.
     * @var MiddlewareChainInterface
     */
    private ?MiddlewareChainInterface $middlewareChain = null;

    public function __construct()
    {
        // neat exception
        //new ExceptionHandler($this->httpResponse, fn() => $this->shutdown());
    }

    /**
     * Application context getter.
     */
    public function appContext(): AppContext
    {
        return $this->appContext;
    }

    /**
     * Application context setter.
     */
    public function setAppContext(AppContext $appContext): void
    {
        $this->appContext = $appContext;
    }

    /**
     * HTTP context setter.
     */
    public function setHttpContext(HttpContext $httpContext): void
    {
        $this->httpContext = $httpContext;
    }

    /**
     * Middleware service setter.
     */
    public function setMiddlewareChain(MiddlewareChainInterface $middlewareChain): void
    {
        $this->middlewareChain = $middlewareChain;
    }

    /**
     * Adds custom services to container.
     */
    public function addCustom(string $className): void
    {
        (new $className())->configuredServices($this->appContext()->service());
    }

    /**
     * Runs the application.
     */
    public function run(): void
    {
        // process middleware pipeline
        if ($this->middlewareChain != null) {

            $this->middlewareChain->process($this->httpContext);
        }

        // output response
        $this->output();

        // shutdown
        $this->shutdown();
    }

    /**
     * Outputs application action result.
     */
    public function output(): void
    {
        if ($this->httpContext != null) {

            $this->httpContext->response()->output();
        }
    }

    /**
     * Shutdowns the application.
     */
    public function shutdown(): void
    {
    }
}