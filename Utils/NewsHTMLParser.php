<?php

namespace Hubert\BikeBlog\Utils;

use Avocado\ORM\AvocadoRepository;
use Avocado\Utils\Arrays;
use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Attributes\Resource;
use Hubert\BikeBlog\Configuration\ImagesConfiguration;
use Hubert\BikeBlog\Models\Meter\Meter;
use Hubert\BikeBlog\Models\News\News;
use Hubert\BikeBlog\Services\ImagesService;
use function Hubert\BikeBlog\Controllers\fillEmptySpacesWithZeros;

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
    #[Autowired("newsRepository")]
    private AvocadoRepository $newsRepository;

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

        $parsedContent .= $this->parseStats($news);

        $parsedContent = $opening . $parsedContent . $closing;

        return $this->customHTMLTagsParser->parseTag(new HTMLTag("statystyka"), $content, $parsedContent);
    }

    private function parseStats(News $news): string {
        $EARTH_DISTANCE_IN_KM = 12800;

        /**
         * @var News $news
         * @var News[] $allNews
         */
        $allNews = $this->newsRepository->findMany();
        $allMeters = $this->metersRepository->findMany();

        $newsYear = $news->getDate()->year;
        $newsMonth = $this->fillEmptySpacesWithZeros($news->getDate()->month);

        $routesBeforeCurrentRoute = Arrays::indexOf($allNews, fn($curr) => $curr->getId() === $news->getId());
        $routesInThatMonth = count($this->newsRepository->findMany(["date" => $newsYear . "-" . $newsMonth . "-%"]));
        $summaryBikeTripe = array_reduce($allMeters, fn($prev, $meter) => $prev + $meter->getTripLength(), 0);

        $metersThatYear = array_filter($allMeters, function ($meter) use ($news) {
            $relatedNews = $this->newsRepository->findById($meter->getNewsId());

            return $relatedNews->getDate()->year === $news->getDate()->year;
        });
        $summaryBikeTripeInThatYear = array_reduce($metersThatYear, fn($prev, $meter) => $prev + $meter->getTripLength(), 0);
        $aroundTheWorldPercentThatYear = ($summaryBikeTripeInThatYear * $EARTH_DISTANCE_IN_KM) / 100;
        $aroundTheWorldPercent = ($summaryBikeTripe * $EARTH_DISTANCE_IN_KM) / 100;

        return <<<EOL
                <div class="meter">
                    <ul>
                        <li>Liczba Jazd: $routesBeforeCurrentRoute</li>
                        <li>W tym miesiacu: $routesInThatMonth</li>
                        <li>Przejchalem w $newsYear: $summaryBikeTripeInThatYear km</li>
                        <li>Sumaryczny przebieg roweru: $summaryBikeTripe km</li>
                        <li>Dookola swiata ($newsYear): $aroundTheWorldPercentThatYear %</li>
                        <li>Dookola swiata: $aroundTheWorldPercent %</li>
                    </ul>    
                </div>      
        EOL;
    }

    private function fillEmptySpacesWithZeros(int $n): string {
        $n = $n . "";

        if (strlen($n) == 2) {
            return $n;
        }

        return "0" . $n;
    }
}