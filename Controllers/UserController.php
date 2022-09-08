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
use Hubert\BikeBlog\Helpers\LoggerHelper;
use AvocadoApplication\Attributes\BaseURL;
use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Mappings\PostMapping;
use Hubert\BikeBlog\Exceptions\InvalidRequest;
use AvocadoApplication\Mappings\DeleteMapping;
use Hubert\BikeBlog\Exceptions\UserBusyException;
use Hubert\BikeBlog\Exceptions\UserNotFoundException;
use Hubert\BikeBlog\Exceptions\InvalidUserDataException;
use Hubert\BikeBlog\Utils\Validators\UsersRequestValidators;

#[RestController]
#[BaseURL("/api")]
class UserController {

    #[Autowired("usersRepository")]
    private AvocadoRepository $usersRepository;
    #[Autowired]
    private LoggerHelper $logger;

    /**
     * @throws InvalidRequest
     * @throws AvocadoModelException
     * @throws ReflectionException
     * @throws UserNotFoundException
     * @throws InvalidUserDataException
     */
    #[PostMapping("/v1/users/login/")]
    public function login(AvocadoRequest $request, AvocadoResponse $response): AvocadoResponse {
        $this->logger->logRequest($request);
        UsersRequestValidators::validateLoginRequest($request);

        $login = $request->body['username'];
        $password = $request->body['password'];

        $user = $this->usersRepository->findFirst(["username" => $login]);

        if (!$user) {
            $exp = new UserNotFoundException("User `$login` not found");

            $this->logger->logException($request, $exp);

            throw $exp;
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

        $this->logger->logRequest($request);
        UsersRequestValidators::validateRegisterUserRequest($request);

        $username = $request->body['username'];

        $isUserExists = $this->usersRepository->findFirst(["username" => $username]) !== null;

        if ($isUserExists) {
            $exp = new UserBusyException("Username $username is busy.");
            $this->logger->logException($request, $exp);

            throw $exp;
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
        $this->logger->logRequest($request);
        UsersRequestValidators::validateDeleteUserRequest($request);

        $uuid = $request->params['id'];

        $this->usersRepository->deleteOneById($uuid);

        return $response->json(["message" => "Deleted"])->withStatus(HTTPStatus::OK);
    }
}
