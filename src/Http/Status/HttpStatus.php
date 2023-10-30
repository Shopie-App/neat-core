<?php

declare(strict_types=1);

namespace Neat\Http\Status;

use Neat\Http\ActionResult\ActionResult;

/**
 * 200 OK
 */
function OK(mixed $result = null): ActionResult
{
    return new ActionResult(200, 'OK', $result);
}

/**
 * 201 Created
 */
function Created(mixed $result = null): ActionResult
{
    return new ActionResult(201, 'Created', $result);
}

/**
 * 202 Accepted
 */
function Accepted(mixed $result = null): ActionResult
{
    return new ActionResult(201, 'Accepted', $result);
}

/**
 * 204 No Content
 */
function NoContent(mixed $result = null): ActionResult
{
    return new ActionResult(204, 'No Content', $result);
}

/**
 * 400 Bad Request
 */
function BadRequest(mixed $result = null): ActionResult
{
    return new ActionResult(400, 'Bad Request', $result);
}

/**
 * 401 Unauthorized
 */
function Unauthorized(mixed $result = null): ActionResult
{
    return new ActionResult(401, 'Unauthorized', $result);
}

/**
 * 403 Forbidden
 */
function Forbidden(mixed $result = null): ActionResult
{
    return new ActionResult(403 , 'Forbidden', $result);
}

/**
 * 404 Not Found
 */
function NotFound(mixed $result = null): ActionResult
{
    return new ActionResult(404 , 'Not Found', $result);
}

/**
 * 405 Method Not Allowed
 */
function MethodNotAllowed(mixed $result = null): ActionResult
{
    return new ActionResult(405, 'Method Not Allowed', $result);
}

/**
 * 409 Conflict
 */
function Conflict(mixed $result = null): ActionResult
{
    return new ActionResult(409, 'Conflict', $result);
}

/**
 * 415 Unsupported Media Type
 */
function UnsupportedMediaType(mixed $result = null): ActionResult
{
    return new ActionResult(415, 'Unsupported Media Type', $result);
}

/**
 * 429 Too Many Requests
 */
function TooManyRequests(mixed $result = null): ActionResult
{
    return new ActionResult(429, 'Too Many Requests', $result);
}

/**
 * 500 Internal Server Error
 */
function InternalServerError(mixed $result = null): ActionResult
{
    return new ActionResult(500, 'Internal Server Error', $result);
}

/**
 * 501 Not Implemented
 */
function NotImplemented(mixed $result = null): ActionResult
{
    return new ActionResult(500, 'Not Implemented', $result);
}

/**
 * 503 Service Unavailable
 */
function ServiceUnavailable(mixed $result = null): ActionResult
{
    return new ActionResult(500, 'Service Unavailable', $result);
}