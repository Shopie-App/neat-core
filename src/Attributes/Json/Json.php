<?php

declare(strict_types=1);

namespace Neat\Attributes\Json;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_PARAMETER)]
class Json
{
}