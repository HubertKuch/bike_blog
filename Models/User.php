<?php

namespace Hubert\BikeBlog\Models;

use Ramsey\Uuid\UuidInterface;
use Avocado\ORM\Attributes\Id;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Avocado\ORM\Attributes\Table;
use Avocado\ORM\Attributes\Field;
use Avocado\Router\AvocadoRequest;

#[Table("users")]
class User {
    #[Id]
    private string $id;
    #[Field]
    private string $username;
    #[Field]
    private string $email;
    #[Field]
    private string $passwordHash;
    #[Field]
    private string $ip;
    #[Field]
    private UserRole $role;

    public function __construct(UuidInterface $id, string $username, string $email, string $passwordHash, IP $ip, UserRole $role) {
        $this->id = $id->toString();
        $this->username = $username;
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->ip = $ip->getIp();
        $this->role = $role;
    }

    public function getIp(): IP {
        return IP::from($this->ip);
    }

    public static function from(AvocadoRequest $request): User {
        return new User(
            UuidV4::uuid4(),
            $request->body['username'],
            $request->body['email'],
            User::hashPassword($request->body['password']),
            IP::from($request->getClientIP()),
            UserRole::GUEST
        );
    }

    public static function hashPassword(string $password): string {
        return hash('sha256', $password);
    }

    public function comparePassword(string $password): bool {
        return hash('sha256', $password) === $this->passwordHash;
    }

    public function getId(): string {
        return $this->id;
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPasswordHash(): string {
        return $this->passwordHash;
    }

    public function getRole(): UserRole {
        return $this->role;
    }
}
