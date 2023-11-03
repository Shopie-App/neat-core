<?php

declare(strict_types=1);

namespace Neat\Attributes\Json;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class Json
{
    private string $key;

    public function __construct(string $key = '')
    {
        $this->key = $key;
    }

    public function key(): string
    {
        return $this->key;
    }
}