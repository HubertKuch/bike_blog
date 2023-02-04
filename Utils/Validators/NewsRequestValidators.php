<?php

namespace Hubert\BikeBlog\Utils\Validators;

use Avocado\Router\HttpRequest;
use Hubert\BikeBlog\Exceptions\InvalidRequestException;

class NewsRequestValidators {

    /**
     * @throws InvalidRequestException
     */
    public static function validateNewNewsRequest(HttpRequest $request): void {
        $title = $request->body['title'] ?? null;
        $description = $request->body['endState'] ?? null;
        $date = $request->body['date'] ?? null;

        if ($title && $description && $date && is_string($date)) return;

        throw new InvalidRequestException("Invalid request");
    }

    /**
     * @throws InvalidRequestException
     */
    public static function validateFindByTagRequest(HttpRequest $request): void {
        $tag = $request->params['tag'];

        if ($tag) return;

        throw new InvalidRequestException("Invalid request");
    }

    /**
     * @throws InvalidRequestException
     */
    public static function validateFindByIdRequest(HttpRequest $request): void {
        $id = $request->params['id'] ?? null;

        if ($id) {
            return;
        }

        throw new InvalidRequestException("Invalid request");
    }

    /**
     * @throws InvalidRequestException
     */
    public static function validateUpdateRequest(HttpRequest $request): void {
        $id = $request->params['id'] ?? null;

        if ($id) return;

        throw new InvalidRequestException("Invalid request");
    }
}
