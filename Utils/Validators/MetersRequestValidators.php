<?php

namespace Hubert\BikeBlog\Utils\Validators;

use Avocado\Router\AvocadoRequest;
use Hubert\BikeBlog\Exceptions\InvalidRequest;

class MetersRequestValidators {

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
        $starState = $request->body['description'] ?? null;
        $endState = $request->body['startState'] ?? null;
        $tripLength = $request->body['tripLength'] ?? null;
        $time = $request->body['time'] ?? null;
        $toShow = $request->body['toShow'] ?? null;

        if ($newsId && $maxSpeed && $time && $toShow && $starState && $endState && $tripLength) return;

        throw new InvalidRequest("Invalid request");
    }
}
