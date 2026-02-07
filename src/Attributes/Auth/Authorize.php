<?php

declare(strict_types=1);

namespace Neat\Attributes\Auth;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
class Authorize
{
    public function __construct(public ?string $roles = null) {}
}