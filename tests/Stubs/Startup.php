<?php

declare(strict_types=1);

namespace Neat\Tests\Stubs;

use Shopie\DiContainer\Contracts\ServiceContainerInterface;

class Startup
{
    public function configuredServices(ServiceContainerInterface $service): void
    {
    }
}