<?php

declare(strict_types=1);

use Shopie\DiContainer\ServiceContainer;
use Shopie\DiContainer\ServiceProvider;
use Neat\Contexts\AppContext;
use PHPUnit\Framework\TestCase;
use Shopie\DiContainer\Contracts\ServiceCollectionInterface;

final class AppContextTest extends TestCase
{
    public function testAppContext(): void
    {
        $collection = $this->getMockForAbstractClass(ServiceCollectionInterface::class);

        // init injected services
        $service = new ServiceContainer($collection);
        $provider = new ServiceProvider($collection);

        // init app context
        $appContext = new AppContext();

        // set services
        $appContext->setService($service);
        $appContext->setProvider($provider);

        // assert
        $this->assertInstanceOf(AppContext::class, $appContext);
        $this->assertInstanceOf(ServiceContainer::class, $appContext->service());
        $this->assertInstanceOf(ServiceProvider::class, $appContext->provider());
    }
}