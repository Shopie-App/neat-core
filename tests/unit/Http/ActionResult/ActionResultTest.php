<?php

declare(strict_types=1);

use Neat\Http\ActionResult\JsonResult;
use PHPUnit\Framework\TestCase;

final class ActionResultTest extends TestCase
{
    public function testActionResultConstructorTest(): void
    {
        // init a JSON result
        $actionResult = new JsonResult(404, ['error' => 'Not Found']);

        // assert
        $this->assertEquals(404, $actionResult->httpStatusCode);
        $this->assertEquals('Not Found', $actionResult->result['error']);
        $this->assertEquals('application/json', $actionResult->contentType);
    }

    public function testActionResultExecuteTest(): void
    {
        // init a result
        $actionResult = new JsonResult(200, ['id' => 454]);

        // execute and hold to buffer
        ob_start();

        $actionResult->execute();

        $out = ob_get_contents();

        ob_end_clean();

        // assert
        $this->assertEquals(200, $actionResult->httpStatusCode);
        $this->assertEquals('{"id":454}', $out);
        $this->assertEquals('application/json', $actionResult->contentType);
    }
}