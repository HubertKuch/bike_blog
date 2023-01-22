<?php

namespace Hubert\BikeBlog\Models\User;

use Avocado\ORM\Attributes\Field;
use Avocado\ORM\Attributes\Id;
use Avocado\ORM\Attributes\Table;
use Hubert\BikeBlog\Models\IP;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Ramsey\Uuid\UuidInterface;

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

    public static function from(NewUserDto $userDto, string $ip): User {
        return new User(UuidV4::uuid4(), $userDto->getUsername(), $userDto->getEmail(), User::hashPassword($userDto->getPassword()), IP::from($ip), UserRole::GUEST);
    }

    public function getUsername(): string {
        return $this->username;
    }

    public function getEmail(): string {
        return $this->email;
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

    public function getPasswordHash(): string {
        return $this->passwordHash;
    }

    public function getRole(): UserRole {
        return $this->role;
    }
}
