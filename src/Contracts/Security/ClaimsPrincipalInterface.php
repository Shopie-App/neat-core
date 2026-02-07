<?php

declare(strict_types=1);

namespace Neat\Contracts\Security;

interface ClaimsPrincipalInterface 
{
    /**
     * Set the claims for the current request.
     * 
     * This method intentionally overwrites existing claims. This is required for long-running
     * processes (like FrankenPHP) where the service instance is reused across requests.
     */
    public function setClaims(array $claims): void;

    public function getIdentityName(): ?string;
    
    public function isInRole(string $role): bool;

    public function hasClaim(string $type): bool;

    public function getClaim(string $type): mixed;
}