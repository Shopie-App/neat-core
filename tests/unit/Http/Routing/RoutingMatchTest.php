<?php

declare(strict_types=1);

use Neat\Http\Routing\RoutingInfo;
use Neat\Http\Routing\RoutingMatch;
use Neat\Tests\Stubs\UsersController;
use PHPUnit\Framework\TestCase;

final class RoutingMatchTest extends TestCase
{
    public function testRoutingMatch(): void
    {
        // init route info
        $routeInfo = new RoutingInfo();

        // set needed data
        $routeInfo->setHttpVerb('GET');
        
        // routing match
        $routeMatch = new RoutingMatch();

        // set needed data
        $routeMatch->setRouteInfo($routeInfo);
        $routeMatch->setPathParts(['users', '{param1}', 'owner', '{param2}']);
        $routeMatch->setControllers([UsersController::class]);

        // find route
        $result = $routeMatch->match();

        // assert
        $this->assertTrue($result);
        $this->assertEquals('Neat\Tests\Stubs\UsersController', $routeInfo->controllerName());
        $this->assertEquals('getUserOwner', $routeInfo->actionName());
        $this->assertEquals('{param1}', $routeInfo->parameters()[0]);
        $this->assertEquals('{param2}', $routeInfo->parameters()[1]);
    }
}