<?php

declare(strict_types=1);

namespace Neat\App;

use Neat\App\App;
use Neat\Contexts\AppContextBuilder;
use Neat\Contexts\HttpContextBuilder;
use Neat\Contracts\AppBuilder\AppBuilderInterface;
use Neat\Contracts\Http\MiddlewareChainInterface;
use Neat\Http\MiddlewareChain;
use Neat\Http\Routing\RoutingBuilder;
use Neat\Middleware\ApiKey;
use Neat\Middleware\PoweredBy;
use Neat\Middleware\Routing;
use Neat\Middleware\Security;

/**
 * Application concrete builder.
 */
class AppBuilder implements AppBuilderInterface
{
    /**
     * Add http request/response services flag.
     * @var bool
     */
    private bool $useHttp = false;

    /**
     * Add middleware chain service flag.
     * @var bool
     */
    private bool $useMiddleware = false;

    /**
     * Add routing service flag.
     * @var bool
     */
    private bool $useRouting = false;

    /**
     * Add api key shield middleware flag.
     * @var bool
     */
    private bool $addApiKeyShield = false;

    /**
     * Collection of controllers for routing.
     * @var array
     */
    private array $controllers = [];

    /**
     * Middleware chain service, is passed to application instance
     * @var MiddlewareChainInterface
     */
    private MiddlewareChainInterface $middlewareChain;

    /**
     * Application instance.
     * @var App
     */
    private App $app;

    public function __construct()
    {
        $this->reset();
    }

    public function useHttp(): void
    {
        $this->useHttp = true;
    }

    public function useMiddleware(): void
    {
        $this->useMiddleware = true;

        $this->useHttp = true;
    }

    public function useRouting(): void
    {
        $this->useRouting = true;

        $this->useHttp = true;

        $this->useMiddleware = true;
    }

    public function addEndpoints(array $controllers): void
    {
        $this->useRouting = true;

        $this->useHttp = true;

        $this->useMiddleware = true;

        $this->controllers = $controllers;
    }

    public function addApiKeyShield(): void
    {
        $this->addApiKeyShield = true;
    }

    /**
     * @inheritdoc
     */
    public function build(): App
    {
        // create app context
        $this->createAppContext();

        // add http request/response services
        $this->addHttpContext();

        // add middleware chain service
        $this->addMiddleware();

        // add routing service and middleware
        $this->addRouting();

        // add other middlewares
        $this->addUserDefinedMiddlewares();

        // add user defined services to container collection
        //(new Startup)->configuredServices($this->app->servicesContainer());

        // return app instance
        return $this->app;
    }

    /**
     * Get a new application instance.
     */
    private function reset(): void
    {
        $this->app = new App();
    }

    /**
     * Creates the application context and sets it to application instance.
     */
    private function createAppContext(): void
    {
        $this->app->setAppContext((new AppContextBuilder())->getResult());
    }

    /**
     * Creates the HTTP request/response context and sets it to application instance.
     */
    private function addHttpContext(): void
    {
        if (!$this->useHttp) {
            return;
        }

        $this->app->setHttpContext((new HttpContextBuilder(
            $this->app->appContext()->service(),
            $this->app->appContext()->provider()
        ))->getResult());
    }

    /**
     * Add middleware chain service.
     */
    private function addMiddleware(): void
    {
        if (!$this->useMiddleware) {
            return;
        }

        $this->app->appContext()->service()->addScoped(MiddlewareChainInterface::class, MiddlewareChain::class);

        $this->middlewareChain = $this->app->appContext()->provider()->getService(MiddlewareChainInterface::class);

        // add common middlewares
        $this->middlewareChain->add(Security::class);

        $this->middlewareChain->add(PoweredBy::class);

        // add authentication middlewares

        // set to application
        $this->app->setMiddlewareChain($this->middlewareChain);
    }

    /**
     * Add middleware chain service.
     */
    private function addRouting(): void
    {
        if (!$this->useRouting) {
            return;
        }

        $this->middlewareChain->add(Routing::class, (new RoutingBuilder(
            $this->app->appContext()->service(),
            $this->app->appContext()->provider(),
            $this->controllers
        ))->getResult());
    }

    /**
     * Add other middleware services.
     */
    private function addUserDefinedMiddlewares(): void
    {
        if (!$this->useMiddleware) {
            return;
        }

        if ($this->addApiKeyShield) {
            $this->middlewareChain->add(ApiKey::class);
        }
    }
}