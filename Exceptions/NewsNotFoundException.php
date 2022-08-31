<?php

namespace Hubert\BikeBlog\Exceptions;

use Avocado\HTTP\HTTPStatus;
use Avocado\AvocadoApplication\Attributes\Exceptions\ResponseStatus;

#[ResponseStatus(HTTPStatus::BAD_REQUEST)]
class NewsNotFoundException extends NotFoundException {
}
