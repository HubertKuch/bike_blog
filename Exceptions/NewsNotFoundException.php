<?php

namespace Hubert\BikeBlog\Exceptions;

use Avocado\AvocadoApplication\Attributes\Exceptions\ResponseStatus;
use Avocado\HTTP\HTTPStatus;

#[ResponseStatus(HTTPStatus::NOT_FOUND)]
class NewsNotFoundException extends NotFoundException {
}
