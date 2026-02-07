<?php

declare(strict_types=1);

namespace Neat\App;

use Neat\Contexts\AppContext;
use Neat\Contexts\HttpContext;
use Neat\Contracts\App\AppInterface;
use Neat\Contracts\Contexts\HttpContextInterface;
use Neat\Contracts\Http\RequestInterface;
use Neat\Contracts\Http\ResponseInterface;
use Neat\Contracts\Http\MiddlewareChainInterface;

class App implements AppInterface
{
    /**
     * Application context object.
     * @var AppContext
     */
    private AppContext $appContext;

    private int $requestCount = 0;

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
        $className::configuredServices(
            $this->appContext()->service(),
            $this->appContext()->provider()
        );
    }

    /**
     * Runs the application.
     */
    public function run(?HttpContext $context = null): void
    {
        // Use passed context or resolve from container (FPM fallback)
        $httpContext = $context ?? $this->appContext->provider()->getService(HttpContext::class);

        // If running in a worker loop (context passed), sync the container with the fresh objects
        if ($context !== null) {
            $this->appContext->service()->setObject(HttpContext::class, $context);
            $this->appContext->service()->setObject(RequestInterface::class, $context->request());
            $this->appContext->service()->setObject(ResponseInterface::class, $context->response());
        }

        try {
            // process middleware pipeline
            if ($this->middlewareChain !== null) {

                $this->middlewareChain->process($httpContext);
            }

            // output result
            $this->output($httpContext);

        } finally {
            $this->resetState();

            // optional
            // Only force GC every 100 requests to save CPU cycles
            if (gc_enabled() && ++$this->requestCount % 100 === 0) {
                gc_collect_cycles();
            }
        }
    }

    /**
     * Outputs application action result.
     */
    public function output(HttpContextInterface $context): void
    {
        $context->response()->output();
    }

    /**
     * Resets application's state.
     */
    public function resetState(): void
    {
        $this->appContext->service()->resetAll();
    }
}