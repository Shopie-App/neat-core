<?php

declare(strict_types=1);

use Neat\App\App;
use PHPUnit\Framework\TestCase;

final class AppTest extends TestCase
{
    public function testAppConstructor(): void
    {
        // init app
        $app = new App();

        // assert
        $this->assertInstanceOf(App::class, $app);
    }
}