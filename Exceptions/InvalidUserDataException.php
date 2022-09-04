<?php

namespace Hubert\BikeBlog\Exceptions;

use Exception;
use Avocado\HTTP\HTTPStatus;
use Avocado\AvocadoApplication\Attributes\Exceptions\ResponseStatus;

#[ResponseStatus(HTTPStatus::BAD_REQUEST)]
class InvalidUserDataException extends Exception {
}
