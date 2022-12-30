<?php

namespace Hubert\BikeBlog\Configuration;

use Avocado\AvocadoApplication\Attributes\Configuration;
use Avocado\AvocadoApplication\Attributes\ConfigurationProperties;

#[Configuration]
#[ConfigurationProperties("front-end")]
class FrontEndConfiguration {

    private readonly string $apiBaseURl;

    public function getApiBaseURl(): string {
        return $this->apiBaseURl;
    }

}