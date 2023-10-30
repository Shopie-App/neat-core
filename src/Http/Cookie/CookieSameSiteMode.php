<?php

declare(strict_types=1);

namespace Neat\Http\Cookie;

enum CookieSameSiteMode: string
{
    case Strict = 'SameSite=Strict';

    case Lax = 'SameSite=Lax';

    case None = 'SameSite=None';

    case Unspecified = '';
}