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
        $this->assertArrayHasKey('test_name', $arr);
        //$this->assertArrayHasKey('testCity', $arr);

        $this->assertSame(1, $arr['id']);
        $this->assertSame('This is a test title', $arr['title']);
        $this->assertSame('My name', $arr['test_name']);
        //$this->assertSame('Athens', $arr['testCity']);
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

    private string $test_name;

    // not initialized, will not be added
    private string $testCity;

    public function __construct()
    {
        $this->testId = 1;
        $this->testTitle = 'This is a test title';
        $this->test_name = 'My name';
        //$this->testCity = 'Athens';
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