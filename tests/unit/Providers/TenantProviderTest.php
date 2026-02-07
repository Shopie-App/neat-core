<?php

declare(strict_types=1);

use Neat\Providers\TenantProvider;
use PHPUnit\Framework\TestCase;

final class TenantProviderTest extends TestCase
{
    public function testSetAndGetTenantId(): void
    {
        $provider = new TenantProvider();
        $provider->set('tenant-123');

        $this->assertTrue($provider->has());
        $this->assertSame('tenant-123', $provider->id());
    }

    public function testGetThrowsExceptionWhenNotSet(): void
    {
        $provider = new TenantProvider();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Tenant context not identified.');

        $provider->id();
    }

    public function testSetThrowsExceptionWhenOverwritingWithDifferentId(): void
    {
        $provider = new TenantProvider();
        $provider->set('tenant-A');

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Cannot overwrite tenant context once established.');

        $provider->set('tenant-B');
    }

    public function testSetAllowsSameId(): void
    {
        $provider = new TenantProvider();
        $provider->set('tenant-A');
        $provider->set('tenant-A'); // Should not throw

        $this->assertSame('tenant-A', $provider->id());
    }

    public function testResetClearsState(): void
    {
        $provider = new TenantProvider();
        $provider->set('tenant-123');

        $provider->reset();

        $this->assertFalse($provider->has());
    }
}