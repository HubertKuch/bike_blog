<?php

namespace Hubert\BikeBlog\Utils;

use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Attributes\Resource;
use Hubert\BikeBlog\Configuration\ImagesConfiguration;
use Hubert\BikeBlog\Models\News\News;
use Hubert\BikeBlog\Services\ImagesService;

#[Resource]
class NewsHTMLParser {

    #[Autowired]
    private CustomHTMLTagsParser $customHTMLTagsParser;
    #[Autowired]
    private ImagesService $imagesService;
    #[Autowired]
    private ImagesConfiguration $imagesConfiguration;

    public function __construct() {
    }

    public function parse(News $news): News {
        $newsBody = $news->getDescription();

        $newsBody = self::parseImages($newsBody, $news);

        $news->setDescription($newsBody);

        return $news;
    }

    private function parseImages(string $content, News $news): string {
        $opening = "<div class='images-container'>";
        $newsImages = $this->imagesService->getNewsImages($news->getId());

        foreach ($newsImages as $image) {
            $name = $image->getName();
            $path = $this->imagesConfiguration->getRoot() . $news->getId() . "/" . $image->getName();

            $fileContent = file_get_contents($path);
            $data = base64_encode($fileContent);

            $opening .= "<img src='data: image/png;base64,$data'  alt='$name' />";
        }

        return $this->customHTMLTagsParser->parseTag(new HTMLTag("zdjecia"), $content, $opening . "</div>");
    }
}