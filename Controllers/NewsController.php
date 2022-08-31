<?php

namespace Hubert\BikeBlog\Controllers;

use ReflectionException;
use Avocado\HTTP\HTTPStatus;
use Hubert\BikeBlog\Models\News;
use Avocado\ORM\AvocadoRepository;
use Avocado\Router\AvocadoRequest;
use Avocado\Router\AvocadoResponse;
use Hubert\BikeBlog\Models\NewsDTO;
use Avocado\ORM\AvocadoModelException;
use Avocado\Application\RestController;
use AvocadoApplication\Attributes\BaseURL;
use AvocadoApplication\Mappings\GetMapping;
use Avocado\ORM\AvocadoRepositoryException;
use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Mappings\PostMapping;
use AvocadoApplication\Mappings\DeleteMapping;
use Hubert\BikeBlog\Exceptions\InvalidRequest;
use Hubert\BikeBlog\Utils\Validators\NewsValidator;
use Hubert\BikeBlog\Exceptions\NewsNotFoundException;

#[RestController]
#[BaseURL("/api/v1/news")]
class NewsController {

    #[Autowired("newsRepository")]
    private AvocadoRepository $newsRepository;

    /**
     * @throws AvocadoModelException
     * @throws ReflectionException
     * @throws AvocadoRepositoryException
     * @throws InvalidRequest
     */
    #[PostMapping("/")]
    public function newNews(AvocadoRequest $request, AvocadoResponse $response): AvocadoResponse {
        NewsValidator::validateNewNews($request);

        $news = News::from($request);

        $this->newsRepository->save($news);

        return $response->withStatus(HTTPStatus::CREATED)->json(["message" => "Success"]);
    }

    #[GetMapping("/")]
    public function getAllNews(AvocadoRequest $request, AvocadoResponse $response): AvocadoResponse {
        $news = $this->newsRepository->findMany();

        $newsDTOs = array_map(fn($n) => NewsDTO::from($n), $news);

        return $response->json($newsDTOs);
    }

    /**
     * @throws ReflectionException
     * @throws AvocadoModelException
     * @throws NewsNotFoundException
     */
    #[GetMapping("/:id")]
    public function getNewsById(AvocadoRequest $request, AvocadoResponse $response): AvocadoResponse {
        $id = $request->params['id'];

        $news = $this->newsRepository->findById($id);

        if (!$news) {
            throw new NewsNotFoundException("News with id $id not found.");
        }

        return $response->json([NewsDTO::from($news)]);
    }

    #[GetMapping("/tag/:tag")]
    public function getNewsByTag(AvocadoRequest $request, AvocadoResponse $response) {
        NewsValidator::validateFindByTag($request);

        $tag = $request->params['tag'];

        $news = $this->newsRepository->findMany(["tags" => "%$tag%"]);

        $newsDTOs = array_map(fn($n) => NewsDTO::from($n), $news);

        return $response->withStatus(HTTPStatus::OK)->json($newsDTOs);
    }

    #[DeleteMapping("/")]
    public function deleteNewsById() {
    }
}
