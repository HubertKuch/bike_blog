<?php

namespace Hubert\BikeBlog\Utils\Validators;

use Avocado\Router\AvocadoRequest;

class NewsValidator {

    public static function isValidNews(AvocadoRequest $request): bool {
        $title = $request->body['title'] ?? null;
        $description = $request->body['description'] ?? null;
        $tags = $request->body['tags'] ?? null;

        return $title && $description && is_array($tags);
    }
}
