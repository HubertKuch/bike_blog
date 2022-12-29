<?php

namespace Hubert\BikeBlog\Controllers;

use Avocado\Application\RestController;
use Avocado\AvocadoApplication\Mappings\Produces;
use Avocado\HTTP\ContentType;
use Avocado\Tests\Unit\Application\RequestParam;
use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Attributes\BaseURL;
use AvocadoApplication\Mappings\GetMapping;
use Hubert\BikeBlog\Configuration\GpsFilesConfiguration;
use Hubert\BikeBlog\Utils\GpsLogFile;

#[RestController]
#[BaseURL("/api/v1/gps")]
class GpsController {

    #[Autowired]
    private GpsFilesConfiguration $gpsFilesConfiguration;

    #[GetMapping("/:newsId")]
    #[Produces(ContentType::APPLICATION_JSON)]
    public function getGpsArray(#[RequestParam(name: "newsId", required: true)] string $newsId): array {
        $path = $this->gpsFilesConfiguration->getRoot() . $newsId;

        $dir = scandir($path);
        $dir = array_filter($dir, fn($file) => str_ends_with($file, ".log"));

        $logFile = $dir[key($dir)];
        $path = $this->gpsFilesConfiguration->getRoot() . $newsId . "/" . $logFile;

        $content = file_get_contents($path);

        return GpsLogFile::parseToArray($content);
    }

}