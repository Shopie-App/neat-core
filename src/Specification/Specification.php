<?php

declare(strict_types=1);

namespace Neat\Specification;

use Neat\Contracts\Specification\SpecificationInterface;

abstract class Specification implements SpecificationInterface
{
    abstract public function isSatisfiedBy(mixed $candidate): bool;

    public function and(SpecificationInterface $other): SpecificationInterface 
    {
        return new AndSpecification($this, $other);
    }

    public function andNot(SpecificationInterface $other)  : SpecificationInterface
    {
        return new AndNotSpecification($this, $other);
    }

    public function or(SpecificationInterface $other)  : SpecificationInterface
    {
        return new OrSpecification($this, $other);
    }

    public function orNot(SpecificationInterface $other)  : SpecificationInterface
    {
        return new OrNotSpecification($this, $other);
    }

    public function not(): SpecificationInterface
    {
       return new NotSpecification($this);
    }
}