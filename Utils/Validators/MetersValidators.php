<?php

namespace Hubert\BikeBlog\Utils\Validators;

use Avocado\Router\AvocadoRequest;
use Hubert\BikeBlog\Exceptions\InvalidRequest;

class MetersValidators {

    /**
     * @throws InvalidRequest
     */
    public static function validateGetMetersByNewsIdRequest(AvocadoRequest $request): void {
        $newsId = $request->params['newsId'] ?? null;

        if ($newsId) return;

        throw new InvalidRequest("Invalid request");
    }

    /**
     * @throws InvalidRequest
     */
    public static function validateNewMeterRequest(AvocadoRequest $request): void {
        $newsId = $request->body['newsId'] ?? null;
        $maxSpeed = $request->body['maxSpeed'] ?? null;
        $time = $request->body['time'] ?? null;
        $toShow = $request->body['toShow'] ?? null;

        if ($newsId && $maxSpeed && $time && $toShow) return;

        throw new InvalidRequest("Invalid request");
    }
}
