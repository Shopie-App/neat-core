<?php

declare(strict_types=1);

use Shopie\DiContainer\ServiceCollection;
use Shopie\DiContainer\ServiceContainer;
use Neat\Contexts\AppContext;
use Neat\Helpers\UploadedFile\UploadedFile;
use Neat\Helpers\UploadedFile\UploadedFileCollection;
use Neat\Http\ActionResult\ActionResult;
use Neat\Http\Request;
use Neat\Http\Routing\RoutingAction;
use Neat\Http\Routing\RoutingInfo;
use Neat\Http\Status\HttpStatus;
use Neat\Tests\Stubs\UploadController;
use Neat\Tests\Stubs\User;
use Neat\Tests\Stubs\UsersController;
use PHPUnit\Framework\TestCase;

final class RoutingActionTest extends TestCase
{
    public function testRoutingAction(): void
    {
        // build a mock routing info object
        $routeInfo =  $this->createConfiguredMock(RoutingInfo::class, [
            'route' => '/{param1}/owner/{param2}?queryInt=5674',
            'httpVerb' => 'GET',
            'controllerName' => 'Neat\\Tests\\Stubs\\UsersController',
            'reflectedController' => new ReflectionClass(UsersController::class),
            'actionName' => 'getUserOwner',
            'parameters' => [40, 50],
            'actionParameters' => [
                40,
                new User(),
                'My test string!',
                5674
            ],
        ]);

        // build a mock http request object
        $req = $this->createStub(Request::class);
        $req->expects($this->any())->method('get')->willReturn((object) ['queryInt' => 5674]);

        // add global http status functions
        new HttpStatus;

        // build a service container mock object
        $service = new ServiceContainer(new ServiceCollection());

        // init an app context
        $appContext = new AppContext();

        // add di container service to context
        $appContext->setService($service);

        // init routing action
        $routeAction = new RoutingAction($req, $appContext);

        // set info object
        $routeAction->setRouteInfo($routeInfo);

        // run action
        $actionResult = $routeAction->run();

        // assert result
        $this->assertInstanceOf(ActionResult::class, $actionResult);
        $this->assertEquals(200, $actionResult->httpStatusCode);
        $this->assertIsArray($actionResult->result);
        $this->assertEquals('application/json', $actionResult->contentType);
    }

    public function testRoutingActionUploadedFile(): void
    {
        // files collection
        $file = new UploadedFile(
            'myimage.jpg',
            '/full/path/to/file',
            'image/jpeg',
            '/tmp/phpn3FmFr',
            0,
            15488
        );

        $filesCollection = new UploadedFileCollection();
        $filesCollection->add($file);
        $filesCollection->add($file);

        // build a mock routing info object
        $routeInfo =  $this->createConfiguredMock(RoutingInfo::class, [
            'route' => '/',
            'httpVerb' => 'POST',
            'controllerName' => 'Neat\\Tests\\Stubs\\UploadController',
            'reflectedController' => new ReflectionClass(UploadController::class),
            'actionName' => 'storeUploadedFiles',
            'parameters' => [],
            'actionParameters' => [
                $filesCollection
            ],
        ]);

        // build a mock http request object
        $req = $this->createStub(Request::class);
        //$req->expects($this->any())->method('files')->willReturn(null);

        // add global http status functions
        new HttpStatus;

        // build a service container mock object
        $service = new ServiceContainer(new ServiceCollection());

        // init an app context
        $appContext = new AppContext();

        // add di container service to context
        $appContext->setService($service);

        // init routing action
        $routeAction = new RoutingAction($req, $appContext);

        // set info object
        $routeAction->setRouteInfo($routeInfo);

        // run action
        $actionResult = $routeAction->run();

        // get result back
        ob_start();
        $actionResult->execute();
        $apiOutput = ob_get_contents();
        ob_end_clean();

        // to json
        $json = json_decode($apiOutput);

        // assert
        $this->assertInstanceOf(ActionResult::class, $actionResult);
        $this->assertEquals(200, $actionResult->httpStatusCode);

        $this->assertIsArray($json);
        $this->assertCount(2, $json);
        $this->assertEquals('myimage.jpg', $json[0]->name);
    }
}