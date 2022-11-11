<?php

namespace Hubert\BikeBlog;

use Avocado\Application\Application;
use Avocado\AvocadoApplication\Attributes\Avocado;
use Dotenv\Dotenv;

#[Avocado]
class BikeApplication {

    public static function main(): void {
        self::loadEnvironment();

        session_start();
        self::corsPolicy();

        Application::run(__DIR__);
    }

    public static function loadEnvironment(): void {
        (Dotenv::createImmutable(__DIR__))->load();
    }

    public static function corsPolicy(): void {
        header("Access-Control-Allow-Origin: *" );
        header("Access-Control-Allow-Headers: *");
        header("Access-Control-Allow-Methods: GET,POST,DELETE,PATCH");
    }
}
