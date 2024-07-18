<?php

declare(strict_types=1);

use Neat\Attributes\Json\Json;
use Neat\Helpers\NeatCollection;
use Neat\Http\Utils\Json as JsonMarshaler;
use PHPUnit\Framework\TestCase;

final class JsonTest extends TestCase
{
    public function testSimpleMarshal(): void
    {
        $object = new testSimpleObject();

        $arr = JsonMarshaler::marshal($object);

        $this->assertArrayHasKey('id', $arr);
        $this->assertArrayHasKey('title', $arr);
        $this->assertArrayHasKey('test_name', $arr);
        $this->assertArrayNotHasKey('testCity', $arr);

        $this->assertSame(1, $arr['id']);
        $this->assertSame('This is a test title', $arr['title']);
        $this->assertSame('My name', $arr['test_name']);
    }

    public function testSimpleMarshalCollection(): void
    {
        $collection = new testSimpleObjectCollection();

        $collection->add(new testSimpleObject());
        $collection->add(new testSimpleObject());
        $collection->add(new testSimpleObject());

        $arr = JsonMarshaler::marshal($collection);

        $this->assertCount(3, $arr);

        $this->assertArrayHasKey('id', $arr[0]);
        $this->assertArrayHasKey('title', $arr[0]);
        $this->assertArrayHasKey('test_name', $arr[0]);
        $this->assertArrayNotHasKey('testCity', $arr[0]);

        $this->assertSame(1, $arr[0]['id']);
        $this->assertSame('This is a test title', $arr[0]['title']);
        $this->assertSame('My name', $arr[0]['test_name']);
    }

    public function testComplexMarshal(): void
    {
        $object = new testComplexObject();

        $arr = JsonMarshaler::marshal($object);

        $this->assertArrayHasKey('id', $arr);
        $this->assertArrayHasKey('title', $arr);
        $this->assertArrayHasKey('props', $arr);
        $this->assertArrayHasKey('prop_a', $arr['props']);
        $this->assertArrayHasKey('prop_b', $arr['props']);
    }

    public function testSimpleUnMarshal(): void
    {
        $json = (object) [
            'id' => 101,
            'title' => 'My test title',
            'test_name' => 'My test name'
        ];

        $object = new testSimpleObject();

        JsonMarshaler::unMarshal($json, $object);

        $this->assertSame(101, $object->testId());
        $this->assertSame('My test title', $object->testTitle());
        $this->assertSame('My name', $object->testName());
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
    }

    public function testId(): int
    {
        return $this->testId;
    }

    public function testTitle(): string
    {
        return $this->testTitle;
    }

    public function testName(): string
    {
        return $this->test_name;
    }
}

class testSimpleObjectCollection extends NeatCollection
{
    public function __construct()
    {
    }

    public function items(): array{
        return $this->items;
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