<?php

namespace Hubert\BikeBlog\Utils\Validators;

use Avocado\AvocadoApplication\Attributes\Request\RequestBody;
use Avocado\Router\AvocadoRequest;
use Hubert\BikeBlog\Exceptions\InvalidRequest;
use Hubert\BikeBlog\Models\User\NewUserDto;

class UsersRequestValidators {

    /**
     * @throws InvalidRequest
     */
    public static function validateRegisterUserRequest(AvocadoRequest $request, #[RequestBody] NewUserDto $userDto): void {
        $username = $request->body['username'] ?? null;
        $email = $request->body['email'] ?? null;
        $password = $request->body['password'] ?? null;

        if($username && $email && $password && is_string($username) && is_string($email) && is_string($password) && filter_var($email, FILTER_VALIDATE_EMAIL))
            return;

        throw new InvalidRequest("Invalid request");
    }

    /**
     * @throws InvalidRequest
     */
    public static function validateDeleteUserRequest(AvocadoRequest $request): void {
        $uuid = $request->params['id'] ?? null;

        if ($uuid) {
            return;
        }

        throw new InvalidRequest("Invalid request");
    }

    /**
     * @throws InvalidRequest
     */
    public static function validateLoginRequest(AvocadoRequest $request): void {
        $login = $request->body['username'] ?? null;
        $password = $request->body['password'] ?? null;

        if ($login && $password) {
            return;
        }

        throw new InvalidRequest("Invalid request");
    }
}
