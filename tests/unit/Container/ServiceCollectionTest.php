<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Shopie\DiContainer\ServiceCollection;

final class ServiceCollectionTest extends TestCase
{
    public function testServiceCollection(): void
    {
        // init collection
        $collection = new ServiceCollection();

        // add a service
        $collection->add(TestClassInterface::class, TestClass::class);

        // request the service
        $serviceData = $collection->get(TestClassInterface::class);

        // assert object is of TestClass
        $this->assertEquals(TestClass::class, $serviceData[0]);
    }
}

// test data
interface TestClassInterface
{
}

class TestClass
{
}