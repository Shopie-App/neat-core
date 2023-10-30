<?php

declare(strict_types=1);

namespace Neat\Contracts\Specification;

interface SpecificationInterface
{
    public function isSatisfiedBy(mixed $candidate): bool;

    public function and(SpecificationInterface $other): SpecificationInterface;

    public function andNot(SpecificationInterface $other): SpecificationInterface;

    public function or(SpecificationInterface $other): SpecificationInterface;

    public function orNot(SpecificationInterface $other): SpecificationInterface;

    public function not(): SpecificationInterface;
}