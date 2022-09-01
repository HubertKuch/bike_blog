<?php

namespace Hubert\BikeBlog\Controllers;

use Hubert\BikeBlog\Utils\Views;
use Avocado\Application\RestController;
use AvocadoApplication\Mappings\GetMapping;

#[RestController]
class ViewsController {

    #[GetMapping("/")]
    public function mainPage(): void {
        Views::main();
    }
}
