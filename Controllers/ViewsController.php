<?php

namespace Hubert\BikeBlog\Controllers;

use Avocado\Application\RestController;
use Avocado\ORM\AvocadoRepository;
use Avocado\Router\AvocadoRequest;
use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Mappings\GetMapping;
use Hubert\BikeBlog\Utils\Views;

#[RestController]
class ViewsController {

    #[Autowired]
    private Views $views;
    #[Autowired("newsRepository")]
    private AvocadoRepository $newsRepository;

    #[GetMapping("/")]
    public function mainPage(): void {
        $this->views->main();
    }

    #[GetMapping("/login")]
    public function login(): void {
        $this->views->login();
    }

    #[GetMapping("/edit-news/:id")]
    public function editNews(AvocadoRequest $request): void {
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

    #[GetMapping("/tags")]
    public function tags(): void {
        $this->views->tags();
    }
}
