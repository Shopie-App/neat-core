<?php

declare(strict_types=1);

namespace Neat\App;

use Neat\App\App;
use Neat\Authentication\AuthenticationBuilder;
use Neat\Contexts\AppContextBuilder;
use Neat\Contexts\HttpContextBuilder;
use Neat\Contracts\AppBuilder\AppBuilderInterface;
use Neat\Contracts\Authentication\AuthenticationOptionsInterface;
use Neat\Contracts\Http\MiddlewareChainInterface;
use Neat\Http\MiddlewareChain;
use Neat\Http\Routing\RoutingBuilder;
use Neat\Http\Status\HttpStatus;
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
     * Add authentication service flag.
     * @var bool
     */
    private bool $useAuthentication = false;

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

    public function useAuthentication(): void
    {
        $this->useRouting();

        $this->useAuthentication = true;
    }

    public function addEndpoints(array $controllers): void
    {
        $this->useRouting();

        $this->controllers = $controllers;
    }

    public function addAuthentication(string $provider, AuthenticationOptionsInterface $options): AuthenticationBuilder
    {
        return new AuthenticationBuilder($provider, $options);
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

        // add pre routing middleware
        $this->addPreMiddlewares();

        // add routing service and middleware
        $this->addRouting();

        // add post routing middleware
        $this->addPostMiddlewares();

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

        new HttpStatus;
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
     * Add middleware services that run after common ones and before routing.
     */
    private function addPreMiddlewares(): void
    {
        if (!$this->useMiddleware) {
            return;
        }

        // authentication
        if ($this->useAuthentication) {

            // add authentication middleware
        }
    }

    /**
     * Add middleware services that run after routing.
     */
    private function addPostMiddlewares(): void
    {
        if (!$this->useMiddleware) {
            return;
        }
    }
}