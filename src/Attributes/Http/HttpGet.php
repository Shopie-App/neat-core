<?php

declare(strict_types=1);

namespace Neat\Attributes\Http;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD)]
class HttpGet
{
}