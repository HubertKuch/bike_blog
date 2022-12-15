<?php

namespace Hubert\BikeBlog\Controllers;

use Avocado\Application\RestController;
use Avocado\AvocadoApplication\Attributes\Exceptions\ResponseStatus;
use Avocado\HTTP\HTTPStatus;
use Avocado\Router\AvocadoRequest;
use Avocado\Router\AvocadoResponse;
use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Attributes\BaseURL;
use AvocadoApplication\Mappings\GetMapping;
use Hubert\BikeBlog\Exceptions\InvalidRequest;
use Hubert\BikeBlog\Services\TagsService;

#[RestController]
#[BaseURL("/api")]
class TagsController {

    #[Autowired]
    private TagsService $tagsService;

    #[GetMapping("/v1/tags")]
    #[ResponseStatus(HTTPStatus::OK)]
    public function getAllTags(AvocadoRequest $request, AvocadoResponse $response): array {
        return $this->tagsService->getAllTagsAsDto();
    }

    /**
     * @throws InvalidRequest
     */
    #[GetMapping("/v1/tags/:newsId")]
    #[ResponseStatus(HTTPStatus::OK)]
    public function getTagsRelatedWithNews(AvocadoRequest $request, AvocadoResponse $response): array {
        $newsId = $request->params['newsId'];

        if(!$newsId) {
            throw new InvalidRequest("Missing `newsId` param.");
        }

        $tags = $this->tagsService->getTagsOfNews($newsId);

        if(!$tags) {
            throw new InvalidRequest("News with that `newsId` doesn't exists.");
        }

        return $this->tagsService->parseArrayToDto($tags);
    }

}