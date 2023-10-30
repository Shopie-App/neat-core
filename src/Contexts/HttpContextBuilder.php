<?php

declare(strict_types=1);

namespace Neat\Contexts;

use Shopie\DiContainer\Contracts\ServiceContainerInterface;
use Shopie\DiContainer\Contracts\ServiceProviderInterface;
use Neat\Contracts\Contexts\HttpContextBuilderInterface;
use Neat\Contracts\Http\RequestInterface;
use Neat\Contracts\Http\ResponseInterface;
use Neat\Http\Request;
use Neat\Http\Response;

class HttpContextBuilder implements HttpContextBuilderInterface
{
    public function __construct(
        private ServiceContainerInterface $service,
        private ServiceProviderInterface $provider
    ) {
    }

    public function getResult(): HttpContext
    {
        $this->service->addScoped(RequestInterface::class, Request::class);

        $this->service->addScoped(ResponseInterface::class, Response::class);

        $this->service->addScoped(HttpContext::class);

        return $this->provider->getService(HttpContext::class);
    }
}