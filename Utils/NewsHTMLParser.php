<?php

namespace Hubert\BikeBlog\Utils;

use Avocado\ORM\AvocadoRepository;
use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Attributes\Resource;
use Hubert\BikeBlog\Configuration\ImagesConfiguration;
use Hubert\BikeBlog\Models\Meter\Meter;
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
    #[Autowired("metersRepository")]
    private AvocadoRepository $metersRepository;

    public function __construct() {
    }

    public function parse(News $news): News {
        $newsBody = $news->getDescription();

        $newsBody = self::parseImages($newsBody, $news);
        $newsBody = self::parseMeters($newsBody, $news);

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

    private function parseMeters(string $content, News $news): string {
        $opening = "<div class='meters news-meters'>";
        $parsedContent = "";
        $closing = "</div>";

        /** @var Meter[] $meters */
        $meters = $this->metersRepository->findMany(['news_id' => $news->getId()]);

        for ($index = 0; $index < count($meters); $index++) {
            $meter = $meters[$index];
            $meterNo = $index + 1;

            $parsedContent .= <<<EOL
                    <div class="meter">
                        <h2>Licznik nr {$meterNo}</h2>
                        <ul>
                            <li>Poczatkowy stan {$meter->getStartState()}</li>                                      
                            <li>Koncowy stan {$meter->getEndState()}</li>                                      
                            <li>Maksymalna predkosc {$meter->getMaxSpeed()}</li>                                      
                            <li>Czas na liczniku {$meter->getTime()}</li>                                      
                            <li>Czas jazdy {$meter->getTripLength()}</li>                                      
                        </ul>    
                    </div>      
            EOL;
        }

        $parsedContent = $opening . $parsedContent . $closing;

        return $this->customHTMLTagsParser->parseTag(new HTMLTag("statystyka"), $content, $parsedContent);
    }
}