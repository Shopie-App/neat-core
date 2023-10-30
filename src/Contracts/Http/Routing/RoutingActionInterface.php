<?php

declare(strict_types=1);

namespace Neat\Contracts\Http\Routing;

use Neat\Contracts\Http\ActionResult\ActionResultInterface;
use Neat\Http\Routing\RoutingInfo;

interface RoutingActionInterface
{
    public function setRouteInfo(RoutingInfo $routeInfo): void;

    public function run(): ActionResultInterface;
}