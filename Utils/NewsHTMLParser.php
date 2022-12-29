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
        $tag = new HTMLTag("zdjecia");
        $newsImages = $this->imagesService->getNewsImages($news);

        foreach ($newsImages as $image) {
            $name = $image->getName();

            $content = $this->customHTMLTagsParser->parseTag($tag, $content, "<img src='/resources/$name/'  alt='$name' />");
        }

        return $content;
    }
}