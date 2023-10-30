<?php

declare(strict_types=1);

namespace Neat\Tests\Stubs;

use Neat\Contracts\Container\ServiceContainerInterface;

class Startup
{
    public function configuredServices(ServiceContainerInterface $service): void
    {
    }
}