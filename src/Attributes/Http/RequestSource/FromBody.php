<?php

declare(strict_types=1);

namespace Neat\Attributes\Http\RequestSource;

use Attribute;
use Neat\Http\Request;
use Neat\Http\Utils\Json;

#[Attribute(Attribute::TARGET_PARAMETER)]
class FromBody
{
    public function __construct()
    {
    }

    /**
     * Gets object from http request body.
     */
    public function loadObject(string $type, Request $httpRequest): mixed
    {
        return Json::toObject($httpRequest->body(), new $type());
    }
}