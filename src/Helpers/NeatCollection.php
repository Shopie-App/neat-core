<?php

declare(strict_types=1);

namespace Neat\Helpers;

abstract class NeatCollection implements \Iterator, \Countable
{
    private $position;

    protected array $items = [];

    public function add(object $item): void
    {
        $this->items[] = $item;
    }

    public function current(): mixed
    {
        return $this->items[$this->position];
    }

    public function key(): mixed
    {
        return $this->position;
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    public function valid(): bool
    {
        return isset($this->items[$this->position]);
    }

    public function count(): int
    {
        return count($this->items);
    }
}