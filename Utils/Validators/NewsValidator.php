<?php

namespace Hubert\BikeBlog\Utils\Validators;

use Avocado\Router\AvocadoRequest;
use Hubert\BikeBlog\Exceptions\InvalidRequest;

class NewsValidator {

    /**
     * @throws InvalidRequest
     */
    public static function validateNewNews(AvocadoRequest $request): void {
        $title = $request->body['title'] ?? null;
        $description = $request->body['description'] ?? null;
        $tags = $request->body['tags'] ?? null;
        $date = $request->body['date'] ?? null;

        if ($title && $description && $date && is_string($date) && is_array($tags)) return;

        throw new InvalidRequest("Invalid request");
    }

    /**
     * @throws InvalidRequest
     */
    public static function validateFindByTag(AvocadoRequest $request): void {
        $tag = $request->params['tag'];

        if ($tag) return;

        throw new InvalidRequest("Invalid request");
    }
}
