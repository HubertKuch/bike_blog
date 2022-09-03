<?php

namespace Hubert\BikeBlog\Models;

use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Rfc4122\UuidV4;
use Avocado\ORM\Attributes\Table;
use Avocado\Router\AvocadoRequest;

#[Table("users")]
class User {
    private string $id;
    private string $username;
    private string $email;
    private string $passwordHash;
    private string $ip;
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
        return new User(UuidV4::uuid4(), $request->body['username'], $request->body['email'], User::hashPassword($request->body['password']), IP::from($request->getClientIP()), UserRole::GUEST);
    }

    public static function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_DEFAULT);
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
