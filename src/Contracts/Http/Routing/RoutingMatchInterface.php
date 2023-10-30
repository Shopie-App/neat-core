<?php

declare(strict_types=1);

namespace Neat\Contracts\Http\Routing;

use Neat\Http\Routing\RoutingInfo;

interface RoutingMatchInterface
{
    public function setRouteInfo(RoutingInfo $routeInfo): void;

    public function setPathParts(array $pathParts): void;

    public function setControllers(array $controllers): void;
    
    public function match(): bool;
}