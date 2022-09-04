<?php

namespace Hubert\BikeBlog\Exceptions;

use Avocado\HTTP\HTTPStatus;
use Avocado\AvocadoApplication\Attributes\Exceptions\ResponseStatus;

#[ResponseStatus(HTTPStatus::NOT_FOUND)]
class NewsNotFoundException extends NotFoundException {
}
