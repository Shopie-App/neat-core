<?php

declare(strict_types=1);

namespace Neat\Contracts\AppBuilder;

use Neat\App\App;

/**
 * Application builder abstraction.
 */
interface AppBuilderInterface
{
    /**
     * Sets the add http request/response services flag to true.
     */
    public function useHttp(): void;

    /**
     * Sets the add middleware service flag to true.
     */
    public function useMiddleware(): void;

    /**
     * Sets the add routing service flag to true.
     */
    public function useRouting(): void;

    /**
     * Creates the controllers' collection.
     */
    public function addEndpoints(array $controllers): void;

    /**
     * Build ensures components are added in the correct order.
     * 
     * @return App Returns the application instance.
     */
    public function build(): App;
}