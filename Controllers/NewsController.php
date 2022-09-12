<?php

namespace Hubert\BikeBlog\Controllers;

use ReflectionException;
use Avocado\HTTP\HTTPStatus;
use Hubert\BikeBlog\Models\News;
use Avocado\ORM\AvocadoRepository;
use Avocado\Router\AvocadoRequest;
use Avocado\Router\AvocadoResponse;
use Avocado\ORM\AvocadoModelException;
use Hubert\BikeBlog\Models\DTO\NewsDTO;
use Avocado\Application\RestController;
use Hubert\BikeBlog\Helpers\LoggerHelper;
use AvocadoApplication\Attributes\BaseURL;
use AvocadoApplication\Mappings\GetMapping;
use Avocado\ORM\AvocadoRepositoryException;
use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Mappings\PostMapping;
use Hubert\BikeBlog\Models\DTO\NewsByYearDTO;
use Hubert\BikeBlog\Exceptions\InvalidRequest;
use Hubert\BikeBlog\Exceptions\NewsNotFoundException;
use Hubert\BikeBlog\Utils\Validators\NewsRequestValidators;

#[RestController]
#[BaseURL("/api")]
class NewsController {

    #[Autowired("newsRepository")]
    private AvocadoRepository $newsRepository;
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

    #[GetMapping("/v2/news/tag/:tag")]
    public function getNewsByTag(AvocadoRequest $request, AvocadoResponse $response): AvocadoResponse {
        $this->logger->logRequest($request);
        NewsRequestValidators::validateFindByTagRequest($request);

        $tag = $request->params['tag'];

        $news = $this->newsRepository->findMany(["tags" => "%$tag%"]);

        $newsDTOs = NewsDTO::fromArray($news);
        $byYearDTOs = NewsByYearDTO::fromArray($newsDTOs);

        return $response->withStatus(HTTPStatus::OK)->json($byYearDTOs);
    }
}
