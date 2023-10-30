<?php

declare(strict_types=1);

use Neat\App\AppBuilder;
use PHPUnit\Framework\TestCase;

final class AppBuilderTest extends TestCase
{
    public function testAppConstructor(): void
    {
        // init builder
        $builder = new AppBuilder();

        // assert
        $this->assertInstanceOf(AppBuilder::class, $builder);
    }
}