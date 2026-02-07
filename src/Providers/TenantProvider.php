<?php 

declare(strict_types=1);

namespace Neat\Providers;

use RuntimeException;
use Shopie\DiContainer\Contracts\ResettableInterface;

class TenantProvider implements ResettableInterface
{
    private ?string $tenantId = null;

    /**
     * Sets the tenant ID for the current request context.
     * This is typically called by the TenantIdentificationMiddleware.
     */
    public function set(string $id): void
    {
        // Safety: Prevent changing the tenant mid-request if it's already set.
        if ($this->tenantId !== null && $this->tenantId !== $id) {
            throw new RuntimeException("Cannot overwrite tenant context once established.");
        }

        $this->tenantId = $id;
    }

    /**
     * Retrieves the tenant ID.
     * Throws an exception if accessed before identification.
     */
    public function get(): string
    {
        return $this->tenantId ?? throw new RuntimeException("Tenant context not identified.");
    }

    /**
     * Checks if a tenant context exists.
     */
    public function hasTenant(): bool
    {
        return $this->tenantId !== null;
    }

    /**
     * Resets the state for persistent environments.
     * This ensures no data leaks between worker requests.
     */
    public function reset(): void
    {
        $this->tenantId = null;
    }
}