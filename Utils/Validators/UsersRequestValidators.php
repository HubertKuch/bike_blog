<?php

namespace Hubert\BikeBlog\Utils\Validators;

use Avocado\AvocadoApplication\Attributes\Request\RequestBody;
use Avocado\Router\HttpRequest;
use Hubert\BikeBlog\Exceptions\InvalidRequestException;
use Hubert\BikeBlog\Models\User\NewUserDto;

class UsersRequestValidators {

    /**
     * @throws InvalidRequestException
     */
    public static function validateRegisterUserRequest(HttpRequest $request, #[RequestBody] NewUserDto $userDto): void {
        $username = $request->body['username'] ?? null;
        $email = $request->body['email'] ?? null;
        $password = $request->body['password'] ?? null;

        if ($username && $email && $password && is_string($username) && is_string($email) && is_string($password) && filter_var($email,
                FILTER_VALIDATE_EMAIL)) return;

        throw new InvalidRequestException("Invalid request");
    }

    /**
     * @throws InvalidRequestException
     */
    public static function validateDeleteUserRequest(HttpRequest $request): void {
        $uuid = $request->params['id'] ?? null;

        if ($uuid) {
            return;
        }

        throw new InvalidRequestException("Invalid request");
    }

    /**
     * @throws InvalidRequestException
     */
    public static function validateLoginRequest(HttpRequest $request): void {
        $login = $request->body['username'] ?? null;
        $password = $request->body['password'] ?? null;

        if ($login && $password) {
            return;
        }

        throw new InvalidRequestException("Invalid request");
    }
}
