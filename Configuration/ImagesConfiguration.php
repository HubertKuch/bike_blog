<?php

namespace Hubert\BikeBlog\Configuration;

use Avocado\AvocadoApplication\Attributes\Configuration;
use Avocado\AvocadoApplication\Attributes\ConfigurationProperties;

#[Configuration]
#[ConfigurationProperties("images")]
class ImagesConfiguration {
    private readonly string $root;

    public function getRoot(): string {
        return $this->root;
    }
}
