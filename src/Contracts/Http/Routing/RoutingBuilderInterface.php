<?php

declare(strict_types=1);

namespace Neat\Contracts\Http\Routing;

interface RoutingBuilderInterface
{
    /**
     * Builds the router component.
     * 
     * @return RoutingInterface
     */
    public function getResult(): RoutingInterface;
}