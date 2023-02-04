<?php

namespace Hubert\BikeBlog\Helpers;

use Avocado\Router\HttpRequest;
use Exception;
use Hubert\BikeBlog\Logger\Logger;

class LoggerHelper {

    private const LOGGER_DATE_FORMAT = "Y-m-d H:i:s";

    public function __construct(private readonly Logger $logger) {}

    public function logRequest(HttpRequest $request): void {
        $nowTimestamp = time();

        $this->logger->log(["request" => $request, "server" => $_SERVER, "session" => $_SESSION, "at" => $nowTimestamp, "human_at" => date(static::LOGGER_DATE_FORMAT,
            $nowTimestamp)], "request_");
    }

    public function logException(HttpRequest $request, Exception $exception): void {
        $nowTimestamp = time();

        $this->logger->log(["request" => $request, "exception" => $this->getExceptionAsArray($exception), "server" => $_SERVER, "session" => $_SESSION, "at" => $nowTimestamp, "human_at" => date(static::LOGGER_DATE_FORMAT,
            $nowTimestamp)], "exception_");
    }

    private function getExceptionAsArray(Exception $exception): array {
        return ["message"         => $exception->getMessage(), "trace"           => $exception->getTrace(), "trace_as_string" => $exception->getTraceAsString(), "code"            => $exception->getCode(), "file"            => $exception->getFile(), "line"            => $exception->getLine(), "prev"            => $exception->getPrevious(),];
    }
}
