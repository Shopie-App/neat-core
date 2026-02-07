<?php

declare(strict_types=1);

namespace Neat\Contracts\App;

use Neat\Contexts\AppContext;
use Neat\Contracts\Contexts\HttpContextInterface;
use Neat\Contracts\Http\MiddlewareChainInterface;

/**
 * Defines the public contract for the main Application object.
 */
interface AppInterface
{
    public function appContext(): AppContext;

    /**
     * Application context setter.
     */
    public function setAppContext(AppContext $appContext): void;

    /**
     * Middleware service setter.
     */
    public function setMiddlewareChain(MiddlewareChainInterface $middlewareChain): void;

    /**
     * Adds custom services to container.
     */
    public function addCustom(string $className): void;

    /**
     * Runs the application.
     */
    public function run(): void;

    /**
     * Outputs application action result.
     */
    public function output(HttpContextInterface $context): void;

    /**
     * Resets application's state.
     */
    public function resetState(): void;
}