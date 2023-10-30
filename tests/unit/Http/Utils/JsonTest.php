<?php

declare(strict_types=1);

use Neat\Attributes\Json\Json;
use Neat\Http\Utils\Json as UtilJson;
use PHPUnit\Framework\TestCase;

final class JsonTest extends TestCase
{
    public function testSimpleMarshal(): void
    {
        $object = new testSimpleObject();

        $arr = UtilJson::fromObject($object);

        $this->assertArrayHasKey('id', $arr);
        $this->assertArrayHasKey('title', $arr);
    }

    public function testComplexMarshal(): void
    {
        $object = new testComplexObject();

        $arr = UtilJson::fromObject($object);

        $this->assertArrayHasKey('id', $arr);
        $this->assertArrayHasKey('title', $arr);
        $this->assertArrayHasKey('props', $arr);
        $this->assertArrayHasKey('prop_a', $arr['props']);
        $this->assertArrayHasKey('prop_b', $arr['props']);
    }
}

class testSimpleObject
{
    #[Json('id')]
    private int $testId;

    #[Json('title')]
    private string $testTitle;

    public function __construct()
    {
        $this->testId = 1;
        $this->testTitle = 'This is a test title';
    }
}

class testComplexObject
{
    #[Json('id')]
    private int $testId;

    #[Json('title')]
    private string $testTitle;

    #[Json('props')]
    private testComplexProps $testProperties;

    public function __construct()
    {
        $this->testId = 1;
        $this->testTitle = 'This is a test title';
        $this->testProperties = new testComplexProps();
    }
}

class testComplexProps
{
    #[Json('prop_a')]
    private int $testId;

    #[Json('prop_b')]
    private string $testTitle;

    public function __construct()
    {
        $this->testId = 1;
        $this->testTitle = 'This is a test title';
    }
}