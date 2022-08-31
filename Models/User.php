<?php

namespace Hubert\BikeBlog\Models;

use Ramsey\Uuid\UuidInterface;
use Avocado\ORM\Attributes\Table;

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
