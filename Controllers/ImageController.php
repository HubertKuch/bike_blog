<?php

namespace Hubert\BikeBlog\Controllers;

use Avocado\Application\RestController;
use Avocado\AvocadoApplication\Mappings\Produces;
use Avocado\HTTP\ContentType;
use Avocado\Tests\Unit\Application\RequestParam;
use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Attributes\BaseURL;
use AvocadoApplication\Mappings\GetMapping;
use Hubert\BikeBlog\Configuration\ImagesConfiguration;

#[RestController]
#[BaseURL("/resources")]
class ImageController {

    #[Autowired]
    private ImagesConfiguration $imagesConfiguration;

    #[GetMapping("/:newsId/:image")]
    #[Produces(ContentType::IMAGE_PNG)]
    public function getImage(#[RequestParam(name: "newsId", required: true)] string $newsId, #[RequestParam(name: "image", required: true)] string $image): string {
        $path = $this->imagesConfiguration->getRoot() . $newsId . "/" . $image;

        return file_get_contents($path);
    }
}