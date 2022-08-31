<?php

namespace Hubert\BikeBlog\Models;

enum UserRole: string {
    case ADMIN = "admin";
    case GUEST = "guest";
}
