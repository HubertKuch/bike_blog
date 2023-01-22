<?php

namespace Hubert\BikeBlog\Models\User;

class NewUserDto {

    public function __construct(private readonly string $password, private readonly string $username, private readonly string $email) {
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function getPassword(): string {
        return $this->password;
    }

    public function getUsername(): string {
        return $this->username;
    }
}
