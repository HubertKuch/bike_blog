<?php

namespace Hubert\BikeBlog\Controllers;

use Avocado\Application\RestController;
use Avocado\ORM\AvocadoModelException;
use Avocado\ORM\AvocadoRepository;
use Avocado\ORM\AvocadoRepositoryException;
use Avocado\Router\AvocadoRequest;
use Avocado\Router\AvocadoResponse;
use Avocado\Tests\Unit\Application\RequestParam;
use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Attributes\BaseURL;
use AvocadoApplication\Attributes\Resource;
use AvocadoApplication\Mappings\GetMapping;
use AvocadoApplication\Mappings\PatchMapping;
use AvocadoApplication\Mappings\PostMapping;
use Hubert\BikeBlog\Exceptions\InvalidRequestException;
use Hubert\BikeBlog\Exceptions\NewsNotFoundException;
use Hubert\BikeBlog\Helpers\LoggerHelper;
use Hubert\BikeBlog\Models\DTO\NewsByYearDTO;
use Hubert\BikeBlog\Models\DTO\NewsDTO;
use Hubert\BikeBlog\Models\News\News;
use Hubert\BikeBlog\Services\TagsService;
use Hubert\BikeBlog\Utils\NewsHTMLParser;
use Hubert\BikeBlog\Utils\Validators\NewsRequestValidators;
use ReflectionException;

#[Resource]
#[RestController]
#[BaseURL("/api")]
class NewsController {

    #[Autowired("newsRepository")]
    private AvocadoRepository $newsRepository;
    #[Autowired("tagsService")]
    private TagsService $tagsService;
    #[Autowired]
    private LoggerHelper $logger;
    #[Autowired]
    private NewsHTMLParser $newsHTMLParser;
    #[Autowired("metersRepository")]
    private AvocadoRepository $metersRepository;

    public function __construct() {}

    /**
     * @throws AvocadoModelException
     * @throws ReflectionException
     * @throws AvocadoRepositoryException
     * @throws InvalidRequestException
     */
    #[PostMapping("/v1/news/")]
    public function newNews(AvocadoRequest $request, AvocadoResponse $response): array {
        $this->logger->logRequest($request);
        NewsRequestValidators::validateNewNewsRequest($request);

        $news = News::from($request);

        $this->newsRepository->save($news);

        return ["message" => "Success"];
    }

    /**
     * @throws AvocadoModelException
     * @throws ReflectionException
     */
    #[GetMapping("/v2/news/")]
    public function getAllNews(): array {
        $news = $this->newsRepository->findMany();
        $newsDTOs = array_map(fn($n) => NewsDTO::from($n), $news);

        return NewsByYearDTO::fromArray($newsDTOs);
    }

    /**
     * @throws ReflectionException
     * @throws AvocadoModelException
     * @throws InvalidRequestException
     * @throws NewsNotFoundException
     */
    #[GetMapping("/v1/news/:id")]
    public function getNewsById(#[RequestParam(name: "id", required: true)] string $id): NewsDTO {
        $news = $this->newsRepository->findById($id);

        if (!$news) {
            throw new NewsNotFoundException("News with id $id not found.");
        }

        $news = $this->newsHTMLParser->parse($news);

        return NewsDTO::from($news);
    }

    /**
     * @throws AvocadoModelException
     * @throws InvalidRequestException
     * @throws NewsNotFoundException
     * @throws ReflectionException
     */
    #[GetMapping("/v3/news/tag/:tag")]
    public function getNewsByTag(AvocadoRequest $request, #[RequestParam(name: "tag")] string $id): array {
        $this->logger->logRequest($request);

        $tag = $this->tagsService->getTagById($id);

        if (!$tag) {
            throw new NewsNotFoundException();
        }

        $newsTags = $this->tagsService->getNewsTagByTagId($tag->getId());
        $news = array_map(fn($newsTag) => $this->newsRepository->findById($newsTag->getNewsId()), $newsTags);
        $newsDTOs = NewsDTO::fromArray($news);

        return NewsByYearDTO::fromArray($newsDTOs);
    }

    /**
     * @throws InvalidRequestException
     */
    #[PatchMapping("/v1/news/:id")]
    public function updateNewsById(AvocadoRequest $request): NewsDTO {
        $this->logger->logRequest($request);
        NewsRequestValidators::validateFindByTagRequest($request);
        NewsRequestValidators::validateNewNewsRequest($request);

        $id = $request->params['id'];
        $this->newsRepository->updateById(["title" => $request->params['title'], "description" => $request->params['description'], "tags" => implode(';', $request->params['tags']), "date" => $request->params['date'],], $id);

        return NewsDTO::from($this->newsRepository->findById($id));
    }
}
