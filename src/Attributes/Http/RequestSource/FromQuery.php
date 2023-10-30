<?php

declare(strict_types=1);

namespace Neat\Attributes\Http\RequestSource;

use Attribute;
use Neat\Http\Request;

#[Attribute(Attribute::TARGET_PARAMETER)]
class FromQuery
{
    public function __construct(private ?string $name = null)
    {
    }

    /**
     * Gets object from http get query parameter.
     */
    public function loadObject(string $key, Request $httpRequest): mixed
    {
        if ($this->name !== null) {

            return $httpRequest->get()->{$this->name} ?? null;
        }

        return $httpRequest->get()->$key ?? null;
    }
}