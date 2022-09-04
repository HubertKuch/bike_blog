<?php

namespace Hubert\BikeBlog\Helpers;

use Exception;
use Avocado\Router\AvocadoRequest;
use Hubert\BikeBlog\Logger\Logger;

class LoggerHelper {

    private const LOGGER_DATE_FORMAT = "Y-m-d H:i:s";

    public function __construct(
        private readonly Logger $logger
    ) {
    }

    public function logRequest(AvocadoRequest $request): void {
        $nowTimestamp = time();

        $this->logger->log([
            "request"  => $request,
            "server"   => $_SERVER,
            "session"  => $_SESSION,
            "at"       => $nowTimestamp,
            "human_at" => date(static::LOGGER_DATE_FORMAT, $nowTimestamp)
        ], "request_");
    }

    public function logException(AvocadoRequest $request, Exception $exception): void {
        $nowTimestamp = time();

        $this->logger->log([
            "request"   => $request,
            "exception" => $exception,
            "server"    => $_SERVER,
            "session"   => $_SESSION,
            "at"        => $nowTimestamp,
            "human_at"  => date(static::LOGGER_DATE_FORMAT, $nowTimestamp)
        ], "exception_");
    }
}
