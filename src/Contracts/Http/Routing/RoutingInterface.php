<?php

declare(strict_types=1);

namespace Neat\Contracts\Http\Routing;

use Neat\Contracts\Http\ActionResult\ActionResultInterface;

interface RoutingInterface
{
    public function match(string $uri, string $httpVerb): bool;

    public function runAction(): ActionResultInterface;
}