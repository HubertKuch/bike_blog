<?php

namespace Hubert\BikeBlog\Controllers;

use Hubert\BikeBlog\Utils\Views;
use Avocado\Application\RestController;
use AvocadoApplication\Mappings\GetMapping;
use AvocadoApplication\Attributes\Autowired;

#[RestController]
class ViewsController {

    #[Autowired]
    private Views $views;

    #[GetMapping("/")]
    public function mainPage(): void {
        $this->views->main();
    }

    #[GetMapping("/login")]
    public function login(): void {
        $this->views->login();
    }

    #[GetMapping("/edit-news")]
    public function editNews(): void {
        if (!isset($_SESSION['user'])) {
            header("Location: login");
        }

        $this->views->editNews();
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
}
