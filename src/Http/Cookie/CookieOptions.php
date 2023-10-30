<?php

declare(strict_types=1);

namespace Neat\Http\Cookie;

use DateTime;

class CookieOptions
{
    private string $domain;

    private DateTime $expires;

    private bool $httpOnly;

    private int $maxAge;

    private string $path;

    private CookieSameSiteMode $sameSite;

    private bool $secure;

    public function __construct()
    {
    }
}