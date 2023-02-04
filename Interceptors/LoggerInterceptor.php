<?php

namespace Hubert\BikeBlog\Interceptors;

use Avocado\AvocadoApplication\Interceptors\Utils\WebRequestHandler;
use Avocado\AvocadoApplication\Interceptors\WebRequestAnnotationInterceptorAdapter;
use Avocado\Router\HttpRequest;
use Avocado\Router\HttpResponse;
use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Attributes\Resource;
use Hubert\BikeBlog\Helpers\LoggerHelper;

#[Resource]
class LoggerInterceptor implements WebRequestAnnotationInterceptorAdapter {

    #[Autowired]
    private readonly LoggerHelper $loggerHelper;

    public function __construct() {}

    function preHandle(HttpRequest $request, HttpResponse $response, WebRequestHandler $handler): bool {
        $this->loggerHelper->logRequest($request);

        return true;
    }

}