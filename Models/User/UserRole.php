<?php

namespace Hubert\BikeBlog\Models\User;

enum UserRole: string {
    case ADMIN = "admin";
    case GUEST = "guest";
}
