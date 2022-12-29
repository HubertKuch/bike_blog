<?php

namespace Hubert\BikeBlog\Controllers;

use Avocado\Application\RestController;
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
    //#[Produces(ContentType::IMAGE_PNG)]
    public function getImage(#[RequestParam(name: "newsId", required: true)] string $newsId, #[RequestParam(name: "image", required: true)] string $image) {
        $path = $this->imagesConfiguration->getRoot() . $newsId . "/" . $image;
        $size = filesize($path);

        //header("Content-Length: {$size}");
        //header("Content-Type: image/png");
        //
        //header("Cache-Control: no-store, no-cache, must-revalidate");
        //header("Cache-Control: post-check=0, pre-check=0", false);
        //header("Pragma: no-cache");
        //header("Connection: close");
        //
        //$fileContent = file_get_contents($path);
        //$data = base64_encode($fileContent);
        //

    }
}