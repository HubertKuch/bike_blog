<?php

namespace Hubert\BikeBlog\Utils;

use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Attributes\Resource;
use Hubert\BikeBlog\Models\News\News;
use Hubert\BikeBlog\Services\ImagesService;

#[Resource]
class NewsHTMLParser {

    #[Autowired]
    private CustomHTMLTagsParser $customHTMLTagsParser;
    #[Autowired]
    private ImagesService $imagesService;

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
        $newsImages = $this->imagesService->getNewsImages($news);

        foreach ($newsImages as $image) {
            $name = $image->getName();

            $opening .= "<img src='resources/{$news->getId()}/$name/'  alt='$name' />";
        }

        return $this->customHTMLTagsParser->parseTag(new HTMLTag("zdjecia"), $content, $opening . "</div>");
    }
}