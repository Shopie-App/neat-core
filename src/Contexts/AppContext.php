<?php

declare(strict_types=1);

namespace Neat\Contexts;

use Shopie\DiContainer\Contracts\ServiceContainerInterface;
use Shopie\DiContainer\Contracts\ServiceProviderInterface;

class AppContext
{
    /**
     * DI Container.
     * @var ServiceContainerInterface
     */
    private ServiceContainerInterface $service;

    /**
     * Service provider.
     * @var ServiceProviderInterface
     */
    private ServiceProviderInterface $provider;

    public function __construct()
    {
    }

    /**
     * DI container getter.
     * 
     * @return ServiceContainerInterface
     */
    public function service(): ServiceContainerInterface
    {
        return $this->service;
    }

    /**
     * Service provider getter.
     * 
     * @return ServiceProviderInterface
     */
    public function provider(): ServiceProviderInterface
    {
        return $this->provider;
    }

    /**
     * DI container setter.
     */
    public function setService(ServiceContainerInterface $service): void
    {
        $this->service = $service;
    }

    /**
     * Service provider setter.
     */
    public function setProvider(ServiceProviderInterface $provider): void
    {
        $this->provider = $provider;
    }
}