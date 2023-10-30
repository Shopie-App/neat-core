<?php

declare(strict_types=1);

namespace Neat\Http\Routing;

use Neat\Contracts\Http\Routing\RoutingActionInterface;
use Neat\Contracts\Http\Routing\RoutingInterface;
use Neat\Contracts\Http\Routing\RoutingMatchInterface;
use Neat\Http\ActionResult\ActionResult;

/**
 * Routing mediator
 */
class Routing implements RoutingInterface
{
    private RoutingInfo $routeInfo;

    public function __construct(
        private RoutingMatchInterface $routeMatch,
        private RoutingActionInterface $routeAction
    )
    {
        $this->routeInfo = new RoutingInfo();
        $this->routeMatch->setRouteInfo($this->routeInfo);
        $this->routeAction->setRouteInfo($this->routeInfo);
    }

    public function setControllers(array $controllers): void
    {
        $this->routeMatch->setControllers($controllers);
    }

    public function match(string $uriPath, string $httpVerb): bool
    {
        // break uri to parts
        $pathParts = $this->uriToArray($uriPath);

        // set parts to processor object
        $this->routeMatch->setPathParts($pathParts);

        // set path to info object
        $this->routeInfo->setRoute($uriPath);

        // set verb to info object
        $this->routeInfo->setHttpVerb($httpVerb);
        
        // process path
        return $this->routeMatch->match();
    }

    public function runAction(): ActionResult
    {
        return $this->routeAction->run();
    }

    public function uriToArray(string $uri): array
    {
        // break uri to parts
        $pathParts = explode('/', $uri);

        // remove first empty element
        if ($pathParts[0] == '') {
            array_shift($pathParts);
        }

        // have other parts?
        if (empty($pathParts)) {
            return [];
        }

        return $pathParts;
    }
}