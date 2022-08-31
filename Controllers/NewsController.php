<?php

namespace Hubert\BikeBlog\Controllers;

use Avocado\HTTP\HTTPStatus;
use Hubert\BikeBlog\Models\News;
use Avocado\ORM\AvocadoRepository;
use Avocado\Router\AvocadoRequest;
use Avocado\Router\AvocadoResponse;
use Avocado\Application\RestController;
use AvocadoApplication\Attributes\BaseURL;
use AvocadoApplication\Mappings\GetMapping;
use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Mappings\PostMapping;
use AvocadoApplication\Mappings\DeleteMapping;
use Hubert\BikeBlog\Utils\Validators\NewsValidator;

#[RestController]
#[BaseURL("/api/v1/news")]
class NewsController {

    #[Autowired("newsRepository")]
    private AvocadoRepository $newsRepository;

    #[PostMapping("/")]
    public function newNews(AvocadoRequest $request, AvocadoResponse $response) {
        $isValid = NewsValidator::isValidNews($request);

        if (!$isValid) return $response->json(["message" => "Invalid request"])->withStatus(HTTPStatus::BAD_REQUEST);

        $news = News::from($request);

        $this->newsRepository->save($news);

        return $response->withStatus(HTTPStatus::CREATED)->json(["message" => "Success"]);
    }

    #[GetMapping("/")]
    public function getAllNews(AvocadoRequest $request, AvocadoResponse $response) {
        $news = $this->newsRepository->findMany();

        $response->json($news);
    }

    #[GetMapping("/:id")]
    public function getNewsById(AvocadoRequest $request, AvocadoResponse $response) {
        $id = $request->params['id'];

        $news = $this->newsRepository->findById($id);

        $response->json([$news]);
    }

    #[GetMapping("/")]
    public function getNewsByTag() {
    }

    #[DeleteMapping("/")]
    public function deleteNewsById() {
    }
}
