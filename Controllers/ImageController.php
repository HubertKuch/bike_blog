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
        $size = filesize($path);

        header("Content-Length: {$size}");
        header("Expires: Mon, 1 Jan 2099 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
        header("Connection: close");

        while (ob_get_level() > 0) {
            ob_end_flush();
        }
        return file_get_contents($path);
    }
}