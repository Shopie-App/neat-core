<?php

declare(strict_types=1);

use Shopie\DiContainer\ServiceCollection;
use Shopie\DiContainer\ServiceContainer;
use Neat\Contexts\AppContext;
use Neat\Http\ActionResult\ActionResult;
use Neat\Http\Request;
use Neat\Http\Routing\Routing;
use Neat\Http\Routing\RoutingAction;
use Neat\Http\Routing\RoutingMatch;
use Neat\Http\Status\HttpStatus;
use Neat\Tests\Stubs\UsersController;
use PHPUnit\Framework\TestCase;

final class RoutingTest extends TestCase
{
    public function testRouting(): void
    {
        new HttpStatus;
        
        // create request mock
        $req = $this->createStub(Request::class);
        $req->expects($this->any())->method('body')->willReturn(json_decode('{"id":"999","title":"Test Product Title","properties":{"id":"3","type_id":"3"}}'));
        $req->expects($this->any())->method('get')->willReturn((object) ['num' => '5674']);

        // routes to test
        $routes = [
            /*['/users', 'GET'],
            ['/users/987584', 'GET'],
            ['/users/987584/group', 'GET'],
            ['/users/find', 'GET'],*/
            ['/users/987584/owner/Test+User+Name', 'GET']/*,
            ['/users', 'POST'],
            ['/users/987584/admin', 'PUT'],
            ['/users/987584', 'DELETE']*/
        ];

        // init di container
        $service = new ServiceContainer(new ServiceCollection());

        // init an app context
        $appContext = new AppContext();

        // add di container service to context
        $appContext->setService($service);

        // routing match
        $routeMatch = new RoutingMatch();

        // set controller
        $routeMatch->setControllers([
            UsersController::class
        ]);

        // init router
        $router = new Routing($routeMatch, new RoutingAction($req, $appContext));

        // match routes and assert
        foreach ($routes as $routeArgs) {

            // match
            $result = $router->match($routeArgs[0], $routeArgs[1]);
            $this->assertTrue($result, 'path: '.$routeArgs[0].' - verb: '.$routeArgs[1]);

            // run
            $result = $router->runAction();
            $this->assertInstanceOf(ActionResult::class, $result);
        }
    }
}