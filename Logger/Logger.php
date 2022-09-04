<?php

namespace Hubert\BikeBlog\Logger;

interface Logger {

    function log(mixed $data, string $prefix): void;
}
