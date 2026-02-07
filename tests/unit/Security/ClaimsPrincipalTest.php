<?php

declare(strict_types=1);

use Neat\Security\ClaimsPrincipal;
use PHPUnit\Framework\TestCase;

final class ClaimsPrincipalTest extends TestCase
{
    public function testGetIdentityNameReturnsSubClaimAsString(): void
    {
        $principal = new ClaimsPrincipal(['sub' => 123]);

        $this->assertSame('123', $principal->getIdentityName());
    }

    public function testGetIdentityNameReturnsNullWhenMissing(): void
    {
        $principal = new ClaimsPrincipal(['other' => 'value']);

        $this->assertNull($principal->getIdentityName());
    }

    public function testSetClaimsOverwritesExistingClaims(): void
    {
        $principal = new ClaimsPrincipal(['sub' => 'original']);
        $principal->setClaims(['sub' => 'new']);

        $this->assertSame('new', $principal->getIdentityName());
    }

    public function testHasClaimReturnsCorrectly(): void
    {
        $principal = new ClaimsPrincipal(['exists' => 1]);

        $this->assertTrue($principal->hasClaim('exists'));
        $this->assertFalse($principal->hasClaim('missing'));
    }

    public function testGetClaimReturnsValueOrNull(): void
    {
        $principal = new ClaimsPrincipal(['key' => 'value']);

        $this->assertSame('value', $principal->getClaim('key'));
        $this->assertNull($principal->getClaim('missing'));
    }

    public function testIsInRoleWithSingleStringRoleIsCaseInsensitive(): void
    {
        $principal = new ClaimsPrincipal(['role' => 'Admin']);

        $this->assertTrue($principal->isInRole('admin'));
        $this->assertTrue($principal->isInRole('ADMIN'));
        $this->assertFalse($principal->isInRole('User'));
    }

    public function testIsInRoleWithArrayRolesIsCaseSensitive(): void
    {
        // Note: Current implementation uses in_array(..., true) which is case-sensitive
        $principal = new ClaimsPrincipal(['roles' => ['Admin', 'User']]);

        $this->assertTrue($principal->isInRole('Admin'));
        $this->assertFalse($principal->isInRole('admin'));
        $this->assertTrue($principal->isInRole('User'));
    }

    public function testIsInRoleFallsBackToRoleClaimForArray(): void
    {
        $principal = new ClaimsPrincipal(['role' => ['Editor']]);

        $this->assertTrue($principal->isInRole('Editor'));
    }
}