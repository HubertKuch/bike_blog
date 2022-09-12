<?php

namespace Hubert\BikeBlog;


use Avocado\Router\AvocadoRouter;

require "vendor/autoload.php";

ini_set('display_errors', 1);
ini_set('default_charset', 'utf-8');
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

AvocadoRouter::useJSON();

BikeApplication::main();

