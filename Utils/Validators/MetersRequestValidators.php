<?php

namespace Hubert\BikeBlog\Utils\Validators;

use Avocado\Router\HttpRequest;
use Hubert\BikeBlog\Exceptions\InvalidRequestException;

class MetersRequestValidators {

    /**
     * @throws InvalidRequestException
     */
    public static function validateGetMetersByNewsIdRequest(HttpRequest $request): void {
        $newsId = $request->params['newsId'] ?? null;

        if ($newsId) return;

        throw new InvalidRequestException("Invalid request");
    }

    /**
     * @throws InvalidRequestException
     */
    public static function validateNewMeterRequest(HttpRequest $request): void {
        self::validateMainData($request);
    }

    /**
     * @param HttpRequest $request
     * @return void
     * @throws InvalidRequestException
     */
    private static function validateMainData(HttpRequest $request): void {
        $newsId = $request->body['newsId'] ?? null;
        $maxSpeed = $request->body['maxSpeed'] ?? null;
        $starState = $request->body['description'] ?? null;
        $endState = $request->body['startState'] ?? null;
        $tripLength = $request->body['tripLength'] ?? null;
        $time = $request->body['time'] ?? null;
        $toShow = $request->body['toShow'] ?? null;

        if ($newsId && $maxSpeed && $time && $toShow && $starState && $endState && $tripLength) return;

        throw new InvalidRequestException("Invalid request");
    }

    /**
     * @throws InvalidRequestException
     */
    public static function validateUpdateMeterRequest(HttpRequest $request): void {
        $id = $request->params['id'] ?? null;

        if (!$id) throw new InvalidRequestException("Invalid request");

        self::validateMainData($request);
    }
}
