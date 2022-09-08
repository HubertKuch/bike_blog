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

    #[GetMapping("/news")]
    public function news(): void {
        $this->views->news();
    }
}
