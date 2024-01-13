<?php

declare(strict_types=1);

namespace Neat\Attributes\Http\RequestSource;

use Attribute;
use Neat\Http\Request;

#[Attribute(Attribute::TARGET_PARAMETER)]
class FromPost
{
    public function __construct(private ?string $name = null)
    {
    }

    /**
     * Gets object from http post.
     */
    public function loadObject(string $key, Request $httpRequest): ?string
    {
        if ($this->name !== null) {

            return $httpRequest->post()->{$this->name} ?? null;
        }

        return $httpRequest->post()->$key ?? null;
    }
}