<?php

declare(strict_types=1);

namespace Neat\Specification;

use Neat\Contracts\Specification\SpecificationInterface;

class AndSpecification extends Specification
{
    public function __construct(
        private SpecificationInterface $leftCondition,
        private SpecificationInterface $rightCondition
    )
    {
    }
    
    public function IsSatisfiedBy(mixed $candidate): bool
    {
        return $this->leftCondition->isSatisfiedBy($candidate) && $this->rightCondition->isSatisfiedBy($candidate);
    }
}