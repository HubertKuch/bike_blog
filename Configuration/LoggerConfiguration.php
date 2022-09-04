<?php

namespace Hubert\BikeBlog\Configuration;

use Hubert\BikeBlog\Logger\FileLogger;
use Hubert\BikeBlog\Helpers\LoggerHelper;
use Avocado\AvocadoApplication\Attributes\Leaf;
use Avocado\AvocadoApplication\Attributes\Configuration;

#[Configuration]
class LoggerConfiguration {

    #[Leaf]
    public function getLoggerHelper(): LoggerHelper {
        return new LoggerHelper(new FileLogger("Logs/"));
    }
}
