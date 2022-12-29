<?php

namespace Hubert\BikeBlog\Configuration;

use Avocado\AvocadoApplication\Attributes\Configuration;
use Avocado\AvocadoApplication\Attributes\ConfigurationProperties;
use Avocado\AvocadoApplication\Attributes\Leaf;
use Hubert\BikeBlog\Helpers\LoggerHelper;
use Hubert\BikeBlog\Logger\FileLogger;

#[Configuration]
#[ConfigurationProperties("logs")]
class LoggerConfiguration {

    private string $root;

    #[Leaf]
    public function getLoggerHelper(): LoggerHelper {
        return new LoggerHelper(new FileLogger($this->root));
    }
}
