<?php

namespace Hubert\BikeBlog\Controllers;

session_start();

use ReflectionException;
use Avocado\HTTP\HTTPStatus;
use Hubert\BikeBlog\Models\User;
use Avocado\ORM\AvocadoRepository;
use Avocado\Router\AvocadoRequest;
use Avocado\Router\AvocadoResponse;
use Avocado\ORM\AvocadoModelException;
use Avocado\Application\RestController;
use Hubert\BikeBlog\Models\DTO\UserDTO;
use AvocadoApplication\Attributes\BaseURL;
use AvocadoApplication\Mappings\GetMapping;
use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Mappings\PostMapping;
use Hubert\BikeBlog\Exceptions\InvalidRequest;
use AvocadoApplication\Mappings\DeleteMapping;
use Hubert\BikeBlog\Exceptions\UserBusyException;
use Hubert\BikeBlog\Utils\Validators\UsersValidator;
use Hubert\BikeBlog\Exceptions\UserNotFoundException;
use Hubert\BikeBlog\Exceptions\InvalidUserDataException;

#[RestController]
#[BaseURL("/api")]
class UserController {

    #[Autowired("usersRepository")]
    private AvocadoRepository $usersRepository;

    /**
     * @throws InvalidRequest
     * @throws AvocadoModelException
     * @throws ReflectionException
     * @throws UserNotFoundException
     * @throws InvalidUserDataException
     */
    #[GetMapping("/v1/users/login/")]
    public function login(AvocadoRequest $request, AvocadoResponse $response): AvocadoResponse {
        UsersValidator::validateLoginRequest($request);

        $login = $request->body['login'];
        $password = $request->body['password'];

        $user = $this->usersRepository->findFirst(["login" => $login]);

        if (!$user) {
            throw new UserNotFoundException("User `$login` not found");
        }

        $isValidPassword = $user->comparePassword($password);

        if (!$isValidPassword) {
            throw new InvalidUserDataException("Login or password is incorrect");
        }

        $_SESSION['user'] = $user;

        return $response->withStatus(HTTPStatus::OK)->json(["message" => "Success"]);
    }

    /**
     * @throws InvalidRequest
     * @throws UserBusyException
     */
    #[PostMapping("/v1/users/")]
    public function registerUser(AvocadoRequest $request, AvocadoResponse $response): AvocadoResponse {
        UsersValidator::validateRegisterUserRequest($request);

        $username = $request->body['username'];

        $isUserExists = $this->usersRepository->findFirst(["username" => $username]) !== null;

        if ($isUserExists) {
            throw new UserBusyException("Username $username is busy.");
        }

        $user = User::from($request);

        $this->usersRepository->save($user);

        return $response->withStatus(HTTPStatus::CREATED)->json(UserDTO::from($user));
    }

    /**
     * @throws InvalidRequest
     */
    #[DeleteMapping("/v1/users/:id")]
    public function deleteUser(AvocadoRequest $request, AvocadoResponse $response) {
        UsersValidator::validateDeleteUserRequest($request);
        $uuid = $request->params['id'];


        $this->usersRepository->deleteOneById($uuid);

        return $response->json(["message" => "Deleted"])->withStatus(HTTPStatus::OK);
    }
}
