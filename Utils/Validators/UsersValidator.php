<?php

namespace Hubert\BikeBlog\Utils\Validators;

use Ramsey\Uuid\Rfc4122\UuidV4;
use Avocado\Router\AvocadoRequest;
use Hubert\BikeBlog\Exceptions\InvalidRequest;

class UsersValidator {

    /**
     * @throws InvalidRequest
     */
    public static function validateRegisterUserRequest(AvocadoRequest $request): void {
        $username = $request->body['username'] ?? null;
        $email = $request->body['email'] ?? null;
        $password = $request->body['email'] ?? null;

        if ($username && $email && $password && is_string($username) && is_string($email) && is_string($password) && filter_var($email, FILTER_VALIDATE_EMAIL)) return;

        throw new InvalidRequest("Invalid request");
    }

    /**
     * @throws InvalidRequest
     */
    public static function validateDeleteUserRequest(AvocadoRequest $request): void {
        $uuid = $request->params['uuid'] ?? null;

        if ($uuid && UuidV4::isValid($uuid)) return;

        throw new InvalidRequest("Invalid request");
    }
}
