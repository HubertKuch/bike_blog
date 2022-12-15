<?php

namespace Hubert\BikeBlog\Controllers;

use Avocado\Application\RestController;
use Avocado\AvocadoApplication\Attributes\Exceptions\ResponseStatus;
use Avocado\AvocadoApplication\Attributes\Request\RequestBody;
use Avocado\HTTP\HTTPStatus;
use Avocado\ORM\AvocadoModelException;
use Avocado\ORM\AvocadoRepository;
use Avocado\Router\AvocadoRequest;
use Avocado\Router\AvocadoResponse;
use Avocado\Tests\Unit\Application\RequestParam;
use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Attributes\BaseURL;
use AvocadoApplication\Mappings\DeleteMapping;
use AvocadoApplication\Mappings\GetMapping;
use AvocadoApplication\Mappings\PostMapping;
use Hubert\BikeBlog\Exceptions\InvalidRequest;
use Hubert\BikeBlog\Exceptions\InvalidUserDataException;
use Hubert\BikeBlog\Exceptions\UserBusyException;
use Hubert\BikeBlog\Exceptions\UserNotFoundException;
use Hubert\BikeBlog\Helpers\LoggerHelper;
use Hubert\BikeBlog\Models\DTO\UserDTO;
use Hubert\BikeBlog\Models\User\NewUserDto;
use Hubert\BikeBlog\Models\User\User;
use Hubert\BikeBlog\Utils\Validators\UsersRequestValidators;
use ReflectionException;

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
    #[PostMapping("/v2/users/login/")]
    #[ResponseStatus(HTTPStatus::OK)]
    public function login(AvocadoRequest $request, AvocadoResponse $response): array {
        $this->logger->logRequest($request);
        UsersRequestValidators::validateLoginRequest($request);

        $login = $request->body['username'];
        $password = $request->body['password'];

        $user = $this->usersRepository->findFirst(["username" => $login]);

        if(!$user) {
            $exp = new UserNotFoundException("User `$login` not found");

            $this->logger->logException($request, $exp);

            throw $exp;
        }

        $isValidPassword = $user->comparePassword($password);

        if (!$isValidPassword) {
            throw new InvalidUserDataException("Login or password is incorrect");
        }

        $_SESSION['user'] = $user;

        return ["message" => "Success", "status" => 200];
    }

    #[GetMapping("/v1/users/logout")]
    public function logout(): array {
        session_unset();

        return ["message" => "logout", "status" => 200];
    }

    #[PostMapping("/v1/users/")]
    #[ResponseStatus(HTTPStatus::CREATED)]
    public function registerUser(AvocadoRequest $request, #[RequestBody] NewUserDto $newUserDto): UserDTO {
        $this->logger->logRequest($request);
        $username = $newUserDto->getUsername();

        $isUserExists = $this->usersRepository->findFirst(["username" => $username]) !== null;

        if($isUserExists) {
            $exp = new UserBusyException("Username $username is busy.");
            $this->logger->logException($request, $exp);

            throw $exp;
        }

        $user = User::from($newUserDto, $request->getClientIP());
        $this->usersRepository->save($user);

        return UserDTO::from($user);
    }

    /**
     * @throws InvalidRequest
     */
    #[DeleteMapping("/v1/users/:id")]
    #[ResponseStatus(HTTPStatus::OK)]
    public function deleteUser(AvocadoRequest $request, #[RequestParam(name: "id", required: true)] string $uuid): array {
        $this->logger->logRequest($request);
        UsersRequestValidators::validateDeleteUserRequest($request);

        $this->usersRepository->deleteOneById($uuid);

        return ["message" => "Deleted"];
    }
}
