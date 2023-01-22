<?php

namespace Hubert\BikeBlog\Configuration;

use Avocado\AvocadoApplication\Attributes\Configuration;
use Avocado\AvocadoApplication\Attributes\ConfigurationProperties;

#[Configuration]
#[ConfigurationProperties("gps")]
class GpsFilesConfiguration {

    private readonly string $root;
    private readonly int $longitudeAndLatitudePrecision;

    public function getRoot(): string {
        return $this->root;
    }

    public function getLongitudeAndLatitudePrecision(): int {
        return $this->longitudeAndLatitudePrecision;
    }
}