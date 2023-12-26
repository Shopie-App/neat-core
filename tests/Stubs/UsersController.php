<?php
namespace Neat\Tests\Stubs;

use Neat\Attributes\Http\HttpDelete;
use Neat\Attributes\Http\HttpGet;
use Neat\Attributes\Http\HttpPost;
use Neat\Attributes\Http\HttpPut;
use Neat\Attributes\Http\RequestSource\FromBody;
use Neat\Attributes\Http\RequestSource\FromQuery;
use Neat\Http\ActionResult\ActionResult;
use Neat\Http\Utils\Json;

use function Neat\Http\Status\BadRequest;
use function Neat\Http\Status\NotFound;
use function Neat\Http\Status\OK;
use function Neat\Http\Status\Unauthorized;

class UsersController
{
    public function __construct()
    {
    }

    /** 
     * Get all users
     */
    #[HttpGet]
    public function getAll(): ActionResult
    {
        return OK();
    }

    /** 
     * Get a user by id. Id could be int ({id:int}) or string ({id})
     */
    #[HttpGet('/{id}')]
    public function getOne(int $userId): ActionResult
    {
        return OK(['id' => $userId]);
    }

    /**
     * Get a user's group. Id is restricted to an int. Returns user not found.
     */
    #[HttpGet('/{id:int}/group')]
    public function getOneGroup(int $userId): ActionResult
    {
        return NotFound();
    }

    /**
     * Find a user by his email. Email comes from GET args:
     * /users/find?email=someone@example.com
     */
    #[HttpGet('/find')]
    public function getOneByEmail(#[FromQuery] string $email): ActionResult
    {
        return OK(['email' => $email]);
    }

    /**
     * A complex path: /stub/453/owner/hello+there?num=802
     */
    #[HttpGet('/{param1}/owner/{param2}')]
    public function getUserOwner(int $testInt, #[FromBody] User $user, string $testString, #[FromQuery(name: 'num')] int $queryInt): ActionResult
    {
        return OK(['param1' => $testInt, 'user' => $user, 'param2' => $testString, 'get_arg' => $queryInt]);
    }

    /**
     * Adds a new entity to some persistence and returns it to output.
     */
    #[HttpPost]
    public function addUser(#[FromBody] User $user): ActionResult
    {
        return OK(Json::marshal($user));
    }

    /**
     * Update operation that failed user authorization.
     */
    #[HttpPut('{id}/admin')]
    public function updateUserAuth(#[FromBody] User $user): ActionResult
    {
        return Unauthorized();
    }

    /**
     * Delete an entity.
     */
    #[HttpDelete('/{id:int}')]
    public function deleteUser(int $userId): ActionResult
    {
        return BadRequest(['id' => $userId]);
    }
}