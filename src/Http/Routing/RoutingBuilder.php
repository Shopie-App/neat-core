<?php

declare(strict_types=1);

namespace Neat\Http\Routing;

use Shopie\DiContainer\Contracts\ServiceContainerInterface;
use Shopie\DiContainer\Contracts\ServiceProviderInterface;
use Neat\Contracts\Http\Routing\RoutingActionInterface;
use Neat\Contracts\Http\Routing\RoutingBuilderInterface;
use Neat\Contracts\Http\Routing\RoutingInterface;
use Neat\Contracts\Http\Routing\RoutingMatchInterface;

class RoutingBuilder implements RoutingBuilderInterface
{
    public function __construct(
        private ServiceContainerInterface $service,
        private ServiceProviderInterface $provider,
        private array $controllers
    ) {
    }

    public function getResult(): RoutingInterface
    {
        $this->service->addEphemeral(RoutingMatchInterface::class, RoutingMatch::class);

        $this->service->addEphemeral(RoutingActionInterface::class, RoutingAction::class);

        $this->service->addEphemeral(RoutingInterface::class, Routing::class);
        
        $routingService = $this->provider->getService(RoutingInterface::class);

        $routingService->setControllers($this->controllers);

        return $routingService;
    }
}