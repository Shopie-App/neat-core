<?php

declare(strict_types=1);

namespace Neat\Http\Status;

use Neat\Http\ActionResult\ActionResult;
use Neat\Http\ActionResult\JsonResult;

/**
 * Composer file autoloading includes it on evey request.
 * Using a dummy class, thes functions are loaded on demand.
 */
class HttpStatus
{
}

/**
 * 200 OK
 */
function OK(object|array $result = null): ActionResult
{
    return new JsonResult(200, $result);
}

/**
 * 201 Created
 */
function Created(object|array $result = null): ActionResult
{
    return new JsonResult(201, $result);
}

/**
 * 202 Accepted
 */
function Accepted(object|array $result = null): ActionResult
{
    return new JsonResult(202, $result);
}

/**
 * 204 No Content
 */
function NoContent(object|array $result = null): ActionResult
{
    return new JsonResult(204, $result);
}

/**
 * 400 Bad Request
 */
function BadRequest(object|array $result = null): ActionResult
{
    return new JsonResult(400, $result);
}

/**
 * 401 Unauthorized
 */
function Unauthorized(object|array $result = null): ActionResult
{
    return new JsonResult(401, $result);
}

/**
 * 403 Forbidden
 */
function Forbidden(object|array $result = null): ActionResult
{
    return new JsonResult(403, $result);
}

/**
 * 404 Not Found
 */
function NotFound(object|array $result = null): ActionResult
{
    return new JsonResult(404, $result);
}

/**
 * 405 Method Not Allowed
 */
function MethodNotAllowed(object|array $result = null): ActionResult
{
    return new JsonResult(405, $result);
}

/**
 * 409 Conflict
 */
function Conflict(object|array $result = null): ActionResult
{
    return new JsonResult(409, $result);
}

/**
 * 415 Unsupported Media Type
 */
function UnsupportedMediaType(object|array $result = null): ActionResult
{
    return new JsonResult(415, $result);
}

/**
 * 429 Too Many Requests
 */
function TooManyRequests(object|array $result = null): ActionResult
{
    return new JsonResult(429, $result);
}

/**
 * 500 Internal Server Error
 */
function InternalServerError(object|array $result = null): ActionResult
{
    return new JsonResult(500, $result);
}

/**
 * 501 Not Implemented
 */
function NotImplemented(object|array $result = null): ActionResult
{
    return new JsonResult(501, $result);
}

/**
 * 503 Service Unavailable
 */
function ServiceUnavailable(object|array $result = null): ActionResult
{
    return new JsonResult(503, $result);
}