<?php

namespace Hubert\BikeBlog\Controllers;

use Avocado\Application\RestController;
use Avocado\AvocadoApplication\Attributes\Exceptions\ResponseStatus;
use Avocado\AvocadoApplication\Attributes\Request\RequestBody;
use Avocado\HTTP\HTTPStatus;
use Avocado\Router\HttpRequest;
use Avocado\Router\HttpResponse;
use Avocado\Tests\Unit\Application\RequestParam;
use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Attributes\BaseURL;
use AvocadoApplication\Mappings\GetMapping;
use AvocadoApplication\Mappings\PutMapping;
use Hubert\BikeBlog\Exceptions\InvalidRequestException;
use Hubert\BikeBlog\Models\DTO\PutTagsCredentials;
use Hubert\BikeBlog\Services\TagsService;

#[RestController]
#[BaseURL("/api")]
class TagsController {

    #[Autowired]
    private TagsService $tagsService;

    #[GetMapping("/v1/tags")]
    #[ResponseStatus(HTTPStatus::OK)]
    public function getAllTags(HttpRequest $request, HttpResponse $response): array {
        return $this->tagsService->getAllTagsAsDto();
    }

    /**
     * @throws InvalidRequestException
     */
    #[GetMapping("/v1/tags/:newsId")]
    #[ResponseStatus(HTTPStatus::OK)]
    public function getTagsRelatedWithNews(HttpRequest $request, HttpResponse $response): array {
        $newsId = $request->params['newsId'];

        if (!$newsId) {
            throw new InvalidRequestException("Missing `newsId` param.");
        }

        $tags = $this->tagsService->getTagsOfNews($newsId);

        if (!$tags) {
            return [];
        }

        return $this->tagsService->parseArrayToDto($tags);
    }

    #[PutMapping("/v1/tags/:newsId")]
    public function putRawTagsIntoNews(#[RequestBody] PutTagsCredentials $credentials, #[RequestParam(name: "newsId", required: true)] string $newsId): void {
        $this->tagsService->removeAllNewsTags($newsId);

        foreach ($credentials->getTags() as $rawTags) {

            $this->tagsService->addTagToNews($this->tagsService->getTagsByName($rawTags), $newsId);
        }
    }
}