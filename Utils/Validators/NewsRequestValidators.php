<?php

namespace Hubert\BikeBlog\Utils\Validators;

use Avocado\Router\AvocadoRequest;
use Hubert\BikeBlog\Exceptions\InvalidRequest;

class NewsRequestValidators {

    /**
     * @throws InvalidRequest
     */
    public static function validateNewNewsRequest(AvocadoRequest $request): void {
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
    public static function validateFindByTagRequest(AvocadoRequest $request): void {
        $tag = $request->params['tag'];

        if ($tag) return;

        throw new InvalidRequest("Invalid request");
    }

    public static function validateFindByIdRequest(AvocadoRequest $request) {
        $id = $request->params['id'] ?? null;

        if ($id) {
            return;
        }

        throw new InvalidRequest("Invalid request");
    }
}
