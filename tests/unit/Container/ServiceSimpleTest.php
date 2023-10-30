<?php

declare(strict_types=1);

use Shopie\DiContainer\ServiceCollection;
use Shopie\DiContainer\ServiceContainer;
use Shopie\DiContainer\ServiceProvider;
use PHPUnit\Framework\TestCase;

final class ServiceSimpleTest extends TestCase
{
    public function testService(): void
    {
        // init service collection
        $collection = new ServiceCollection();

        // init container
        $service = new ServiceContainer($collection);

        // init provider
        $provider = new ServiceProvider($collection);

        // add concrete service
        $service->addScoped(TestControllerNoController::class);

        // request the service
        $testController = $provider->getService(TestControllerNoController::class);

        // run method
        $result = $testController->doSomething();

        // assert
        $this->assertEquals('Hello Simple!', $result);
    }
}

// simple class no controller
class TestControllerNoController
{
    public function doSomething(): string
    {
        return 'Hello Simple!';
    }
}