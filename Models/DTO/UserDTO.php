<?php

namespace Hubert\BikeBlog\Models\DTO;

use Hubert\BikeBlog\Models\User\User;

class UserDTO {

    public function __construct(
        public string $id,
        public string $username,
        public string $email,
        public string $ip,
        public string $role
    ) {
    }

    public static function from(User $user): UserDTO {
        return new UserDTO(
            $user->getId(),
            $user->getUsername(),
            $user->getEmail(),
            $user->getIp()->getIp(),
            $user->getRole()->value);
    }
}
