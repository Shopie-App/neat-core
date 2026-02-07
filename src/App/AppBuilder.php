<?php

declare(strict_types=1);

namespace Neat\App;

use Neat\Auth\JwtTokenParser;
use Neat\Contexts\AppContextBuilder;
use Neat\Contexts\HttpContextBuilder;
use Neat\Contracts\App\AppBuilderInterface;
use Neat\Contracts\App\AppInterface;
use Neat\Contracts\Http\MiddlewareChainInterface;
use Neat\Contracts\Security\ClaimsPrincipalInterface;
use Neat\Http\MiddlewareChain;
use Neat\Http\Routing\RoutingBuilder;
use Neat\Http\Status\HttpStatus;
use Neat\Middleware\AuthorizationMiddleware;
use Neat\Middleware\ClaimsTransformationMiddleware;
use Neat\Middleware\PoweredByMiddleware;
use Neat\Middleware\Routing;
use Neat\Middleware\SecurityHeadersMiddleware;
use Neat\Middleware\TenancyMiddleware;
use Neat\Providers\TenantProvider;
use Neat\Security\ClaimsPrincipal;

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
     * Add JWT claims transformation flag.
     * @var bool
     */
    private bool $useClaimsTransformation = false;

    /**
     * Add tenancy support flag.
     * @var bool
     */
    private bool $useTenancy = false;

    /**
     * Add authorization flag.
     * @var bool
     */
    private bool $useAuthorization = false;

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
     * @var AppInterface
     */
    private AppInterface $app;

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

    public function useClaimsTransformation(): void
    {
        $this->useClaimsTransformation = true;
    }

    public function useTenancy(): void
    {
        $this->useTenancy = true;
    }

    public function useAuthorization(): void
    {
        $this->useAuthorization = true;
    }

    public function addEndpoints(array $controllers): void
    {
        $this->useRouting();

        $this->controllers = $controllers;
    }

    /**
     * @inheritdoc
     */
    public function build(): AppInterface
    {
        // create app context
        $this->createAppContext();

        // add http request/response services
        $this->addHttpContext();

        // add middleware chain service
        $this->addMiddleware();

        // add pre routing middleware
        $this->addPreMiddlewares();

        // add routing middleware
        $this->addRouting();

        // add jwt claims transformation middleware
        $this->addClaimsTransformation();

        // add tenancy middleware
        $this->addTenancy();

        // add authorization middleware
        $this->addAuthorization();

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

        (new HttpContextBuilder(
            $this->app->appContext()->service(),
            $this->app->appContext()->provider()
        ))->getResult();

        // Trigger autoloader to load the file containing global HTTP status functions
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
        $this->middlewareChain->add(SecurityHeadersMiddleware::class);

        $this->middlewareChain->add(PoweredByMiddleware::class);

        // set to application
        $this->app->setMiddlewareChain($this->middlewareChain);
    }

    /**
     * Adds routing service and middleware.
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
     * Adds claims transformation service and middleware.
     */
    private function addClaimsTransformation(): void
    {
        if (!$this->useClaimsTransformation || !$this->useRouting) {
            return;
        }

        // TODO: better encapsulate it in a builder
        $this->app->appContext()->service()->addScoped(JwtTokenParser::class);

        $this->app->appContext()->service()->addScoped(ClaimsPrincipalInterface::class, ClaimsPrincipal::class);

        $this->middlewareChain->add(
            ClaimsTransformationMiddleware::class,
            $this->app->appContext()->provider()->getService(JwtTokenParser::class),
            $this->app->appContext()->provider()->getService(ClaimsPrincipalInterface::class)
        );
    }

    /**
     * Adds tenancy provider and middleware.
     */
    private function addTenancy(): void
    {
        if (!$this->useTenancy || !$this->useClaimsTransformation) {
            return;
        }

        // TODO: better encapsulate it in a builder
        $this->app->appContext()->service()->addScoped(TenantProvider::class);

        $this->middlewareChain->add(
            TenancyMiddleware::class,
            $this->app->appContext()->provider()->getService(ClaimsPrincipalInterface::class),
            $this->app->appContext()->provider()->getService(TenantProvider::class)
        );
    }

    /**
     * Adds authorization and middleware.
     */
    private function addAuthorization(): void
    {
        if (!$this->useAuthorization || !$this->useRouting) {
            return;
        }

        $this->middlewareChain->add(
            AuthorizationMiddleware::class,
            $this->app->appContext()->provider()->getService(ClaimsPrincipalInterface::class)
        );
    }

    /**
     * Add middleware services that run after common ones and before routing.
     */
    private function addPreMiddlewares(): void
    {
        if (!$this->useMiddleware) {
            return;
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