<?php

declare(strict_types=1);

namespace Neat\Tests\Stubs;

use Neat\Attributes\Http\HttpGet;
use Neat\Attributes\Http\HttpPost;
use Neat\Attributes\Http\RequestSource\FromPost;
use Neat\Authentication\Providers\JwtBearer\JwtIssuer;
use Neat\Contracts\Authentication\AuthenticationInterface;
use Neat\Http\ActionResult\ActionResult;

use function Neat\Http\Status\OK;

class AuthController
{
    public function __construct(private AuthenticationInterface $authService)
    {
    }

    #[HttpPost]
    public function loginAction(#[FromPost] string $email, #[FromPost] string $pwd): ActionResult
    {
        $this->authService->signIn();

        $token = (new JwtIssuer('ffff', 'gfggg'))->issue(121, 300);
        return OK(['token' => $token]);
    }

    #[HttpGet('/sign-out')]
    public function logoutAction(): ActionResult
    {
        return OK();
    }

    #[HttpGet]
    public function authenticateAction(): ActionResult
    {
        return OK();
    }
}