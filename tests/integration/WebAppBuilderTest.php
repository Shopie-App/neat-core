<?php

declare(strict_types=1);

use Neat\App\App;
use Neat\App\AppBuilder;
use PHPUnit\Framework\TestCase;

final class WebAppBuilderTest extends TestCase
{
    public function testWebAppBuilder(): void
    {
        // init builder
        $builder = new AppBuilder();

        // add services

        // add middlewares
        $builder->addApiKeyShield();

        // build
        $app = $builder->build();

        // run
        $app->run();

        // assert
        $this->assertInstanceOf(App::class, $app);
    }
}