<?php

namespace Hubert\BikeBlog\Configuration;

use Avocado\AvocadoApplication\Attributes\Configuration;
use Avocado\AvocadoApplication\Attributes\ConfigurationProperties;

#[Configuration]
#[ConfigurationProperties("gps")]
class GpsFilesConfiguration {

    private string $root;

    public function getRoot(): string {
        return $this->root;
    }
}