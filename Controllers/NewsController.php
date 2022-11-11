<?php

namespace Hubert\BikeBlog\Controllers;

use Avocado\Application\RestController;
use Avocado\HTTP\HTTPStatus;
use Avocado\ORM\AvocadoModelException;
use Avocado\ORM\AvocadoRepository;
use Avocado\ORM\AvocadoRepositoryException;
use Avocado\Router\AvocadoRequest;
use Avocado\Router\AvocadoResponse;
use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Attributes\BaseURL;
use AvocadoApplication\Mappings\GetMapping;
use AvocadoApplication\Mappings\PatchMapping;
use AvocadoApplication\Mappings\PostMapping;
use Hubert\BikeBlog\Exceptions\InvalidRequest;
use Hubert\BikeBlog\Exceptions\NewsNotFoundException;
use Hubert\BikeBlog\Helpers\LoggerHelper;
use Hubert\BikeBlog\Models\DTO\NewsByYearDTO;
use Hubert\BikeBlog\Models\DTO\NewsDTO;
use Hubert\BikeBlog\Models\News;
use Hubert\BikeBlog\Services\TagsService;
use Hubert\BikeBlog\Utils\Validators\NewsRequestValidators;
use ReflectionException;

#[RestController]
#[BaseURL("/api")]
class NewsController {

    #[Autowired("newsRepository")]
    private AvocadoRepository $newsRepository;
    #[Autowired("tagsService")]
    private TagsService $tagsService;
    #[Autowired]
    private LoggerHelper $logger;

    /**
     * @throws AvocadoModelException
     * @throws ReflectionException
     * @throws AvocadoRepositoryException
     * @throws InvalidRequest
     */
    #[PostMapping("/v1/news/")]
    public function newNews(AvocadoRequest $request, AvocadoResponse $response): AvocadoResponse {
        $this->logger->logRequest($request);
        NewsRequestValidators::validateNewNewsRequest($request);

        $news = News::from($request);

        $this->newsRepository->save($news);

        return $response->withStatus(HTTPStatus::CREATED)->json(["message" => "Success"]);
    }

    /**
     * @throws AvocadoModelException
     * @throws ReflectionException
     */
    #[GetMapping("/v2/news/")]
    public function getAllNews(AvocadoRequest $request, AvocadoResponse $response): AvocadoResponse {
        $this->logger->logRequest($request);
        $news = $this->newsRepository->findMany();
        $newsDTOs = array_map(fn($n) => NewsDTO::from($n), $news);
        $newsByYearDTOs = NewsByYearDTO::fromArray($newsDTOs);

        return $response->json($newsByYearDTOs)->withStatus(HTTPStatus::OK);
    }

    /**
     * @throws ReflectionException
     * @throws AvocadoModelException
     * @throws InvalidRequest
     * @throws NewsNotFoundException
     */
    #[GetMapping("/v1/news/:id")]
    public function getNewsById(AvocadoRequest $request, AvocadoResponse $response): AvocadoResponse {
        $this->logger->logRequest($request);
        NewsRequestValidators::validateFindByIdRequest($request);

        $id = $request->params['id'] ?? null;

        $news = $this->newsRepository->findById($id);

        if (!$news) {
            throw new NewsNotFoundException("News with id $id not found.");
        }

        return $response->json([NewsDTO::from($news)]);
    }

    #[GetMapping("/v3/news/tag/:tag")]
    public function getNewsByTag(AvocadoRequest $request, AvocadoResponse $response): AvocadoResponse {
        $this->logger->logRequest($request);
        NewsRequestValidators::validateFindByTagRequest($request);

        $byTag = $request->params['tag'];
        $tag = $this->tagsService->getTagsByName($byTag);

        if(!$tag) {
            throw new NewsNotFoundException();
        }

        $newsTags = $this->tagsService->getNewsTagByTagId($tag->getId());
        $news = array_map(fn($newsTag) => $this->newsRepository->findById($newsTag->getNewsId()), $newsTags);
        $newsDTOs = NewsDTO::fromArray($news);
        $byYearDTOs = NewsByYearDTO::fromArray($newsDTOs);

        return $response->withStatus(HTTPStatus::OK)->json($byYearDTOs);
    }


    #[GetMapping("/v1/news/tag/tags")]
    public function getTags(AvocadoRequest $request, AvocadoResponse $response): AvocadoResponse {
       // TO IMPLEMENT

        return $response->json(["ISE"])->withStatus(HTTPStatus::INTERNAL_SERVER_ERROR);
    }

    /**
     * @throws InvalidRequest
     */
    #[PatchMapping("/v1/news/:id")]
    public function updateNewsById(AvocadoRequest $request, AvocadoResponse $response) {
        $this->logger->logRequest($request);
        NewsRequestValidators::validateFindByTagRequest($request);
        NewsRequestValidators::validateNewNewsRequest($request);

        $id = $request->params['id'];
        $this->newsRepository->updateById([
            "title" => $request->params['title'],
            "description" => $request->params['description'],
            "tags" => implode(';', $request->params['tags']),
            "date" => $request->params['date'],
        ], $id);

        $response->withStatus(HTTPStatus::OK)->json(NewsDTO::from($this->newsRepository->findById($id)));
    }
}
