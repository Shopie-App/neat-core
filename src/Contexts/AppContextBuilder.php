<?php

declare(strict_types=1);

namespace Neat\Contexts;

use Neat\Contracts\Contexts\AppContextBuilderInterface;
use Shopie\DiContainer\ServiceCollection;
use Shopie\DiContainer\ServiceContainer;
use Shopie\DiContainer\ServiceProvider;

class AppContextBuilder implements AppContextBuilderInterface
{
    public function __construct()
    {
    }

    public function getResult(): AppContext
    {
        // init di container collection
        $collection = new ServiceCollection();

        // init di container
        $container = new ServiceContainer($collection);

        // init service provider
        $provider = new ServiceProvider($collection);

        // add application context to container
        $container->addScoped(AppContext::class);

        // init context
        $context = $provider->getService(AppContext::class);

        // set container to context
        $context->setService($container);

        // set provider to context
        $context->setProvider($provider);

        // return the AppContext
        return $context;
    }
}