<?php

declare(strict_types=1);

namespace Neat\Specification;

use Neat\Contracts\Specification\SpecificationInterface;

class NotSpecification extends Specification
{
    public function __construct(private SpecificationInterface $spec)
    {
    }
    
    public function IsSatisfiedBy(mixed $candidate): bool
    {
        return !$this->spec->isSatisfiedBy($candidate);
    }
}