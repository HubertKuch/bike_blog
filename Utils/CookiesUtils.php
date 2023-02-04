<?php

namespace Hubert\BikeBlog\Utils;

use Carbon\Carbon;

class CookiesUtils {

    public static function setCookie(string $name, string $value, Carbon $expiringDate): void {
        setcookie($name, $value, $expiringDate->timestamp, "/", secure: false, httponly: false);
    }

}