<?php

namespace Hubert\BikeBlog\Controllers;

use Avocado\Application\RestController;
use Avocado\ORM\AvocadoRepository;
use Avocado\Router\HttpRequest;
use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Mappings\GetMapping;
use Hubert\BikeBlog\Utils\Views;

#[RestController]
class ViewsController {

    #[Autowired]
    private Views $views;
    #[Autowired("newsRepository")]
    private AvocadoRepository $newsRepository;
    #[Autowired("imagesRepository")]
    private AvocadoRepository $imagesRepository;

    #[GetMapping("/")]
    public function mainPage(): void {
        $this->views->main();
    }

    #[GetMapping("/login")]
    public function login(): void {
        $this->views->login();
    }

    #[GetMapping("/edit-news/:id")]
    public function editNews(HttpRequest $request): void {
        if (!isset($_SESSION['user'])) {
            header("Location: login");
        }

        $newsId = $request->params['id'] ?? null;
        $news = $this->newsRepository->findById($newsId);

        $this->views->editNews($news);
    }

    #[GetMapping("/admin")]
    public function admin(): void {
        if (!isset($_SESSION['user'])) {
            header("Location: login");
        }

        $this->views->admin();
    }

    #[GetMapping("/news")]
    public function news(): void {
        $this->views->news();
    }

    #[GetMapping("/images")]
    public function images(): void {
        $news = $this->newsRepository->findMany();
        $images = $this->imagesRepository->findMany();

        $this->views->images($news, $images);
    }

    #[GetMapping("/tags")]
    public function tags(): void {
        $this->views->tags();
    }

    #[GetMapping("/contact")]
    public function contact(): void {
        $this->views->contact();
    }
}
