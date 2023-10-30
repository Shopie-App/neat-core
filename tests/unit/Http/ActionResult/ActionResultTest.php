<?php

declare(strict_types=1);

use Neat\Http\ActionResult\ActionResult;
use PHPUnit\Framework\TestCase;

final class ActionResultTest extends TestCase
{
    public function testActionResultConstructorTest(): void
    {
        // init a result
        $actionResult = new ActionResult(404, 'Not Found');

        // assert
        $this->assertEquals(404, $actionResult->httpStatusCode());
        $this->assertEquals('Not Found', $actionResult->httpStatusReason());
        $this->assertNull($actionResult->result());
        $this->assertEquals('text/plain', $actionResult->contentType());
    }

    public function testActionResultExecuteTest(): void
    {
        // init a result
        $actionResult = new ActionResult(200, 'OK', ['id' => 454]);

        // execute and hold to buffer
        ob_start();

        $actionResult->execute();

        $out = ob_get_contents();

        ob_end_clean();

        // assert
        $this->assertEquals(200, $actionResult->httpStatusCode());
        $this->assertEquals('OK', $actionResult->httpStatusReason());
        $this->assertEquals('{"id":454}', $out);
        $this->assertEquals('application/json', $actionResult->contentType());
    }
}