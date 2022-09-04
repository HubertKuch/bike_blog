<?php

namespace Hubert\BikeBlog;

use Dotenv\Dotenv;
use Avocado\Application\Application;
use Avocado\AvocadoApplication\Attributes\Avocado;

#[Avocado]
class BikeApplication {

    public static function main(): void {
        self::loadEnvironment();

        Application::run(__DIR__);
    }

    public static function loadEnvironment(): void {
        (Dotenv::createImmutable(__DIR__))->load();
    }
}
