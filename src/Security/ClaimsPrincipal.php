<?php

declare(strict_types=1);

namespace Neat\Security;

use Neat\Contracts\Security\ClaimsPrincipalInterface;

class ClaimsPrincipal implements ClaimsPrincipalInterface
{
    public function __construct(private array $claims = [])
    {
    }
    
    public function setClaims(array $claims): void
    {
        $this->claims = $claims;
    }

    public function getIdentityName(): ?string
    {
        // 'sub' is the standard JWT claim for Subject (User ID/Name)
        return isset($this->claims['sub']) ? (string) $this->claims['sub'] : null;
    }

    public function isInRole(string $role): bool
    {
        // Handle 'role' or 'roles' claim which can be string or array
        $roles = $this->claims['roles'] ?? $this->claims['role'] ?? [];
        
        if (is_string($roles)) {
            return strcasecmp($roles, $role) === 0;
        }
        
        return in_array($role, (array) $roles, true);
    }

    public function hasClaim(string $type): bool
    {
        return array_key_exists($type, $this->claims);
    }

    public function getClaim(string $type): mixed
    {
        return $this->claims[$type] ?? null;
    }
}