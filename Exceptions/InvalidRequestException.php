<?php

namespace Hubert\BikeBlog\Exceptions;

use Avocado\AvocadoApplication\Attributes\Exceptions\ResponseStatus;
use Avocado\HTTP\HTTPStatus;
use Exception;

#[ResponseStatus(HTTPStatus::BAD_REQUEST)]
class InvalidRequestException extends Exception {
}
