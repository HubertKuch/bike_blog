<?php

namespace Hubert\BikeBlog\Sorting;

use Avocado\ORM\AvocadoModelException;
use Avocado\ORM\AvocadoRepository;
use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Attributes\Resource;
use Hubert\BikeBlog\Models\DTO\NewsByYearDTO;
use Hubert\BikeBlog\Models\DTO\NewsSnippet;
use Hubert\BikeBlog\Models\Meter\Meter;
use ReflectionException;

#[Resource]
class NewsSorting {

    #[Autowired("metersRepository")]
    private readonly AvocadoRepository $metersRepository;
    #[Autowired("newsRepository")]
    private readonly AvocadoRepository $newsRepository;

    public function __construct() {}

    /**
     * @param NewsByYearDTO[] $newsDtos
     * */
    public function sortByDateAsc(array &$newsDtos): void {
        foreach ($newsDtos as $dto) {
            usort($dto->news, fn($a, $b) => strtotime($a->time) - strtotime($b->time));
        }
    }

    /**
     * @param NewsByYearDTO[] $newsDtos
     * */
    public function sortByDateDesc(array &$newsDtos): void {
        foreach ($newsDtos as $dto) {
            usort($dto->news, fn($a, $b) => strtotime($b->time) - strtotime($a->time));
        }
    }


    /**
     * @param array $newsDtos
     * @throws AvocadoModelException
     * @throws ReflectionException
     */
    public function sortByLengthAsc(): array {
        return $this->newsRepository->customWithDataset("
            SELECT id, title, description, date
            FROM (SELECT news.*, m.meter_end_state - m.meter_start_state AS length
            FROM news
                LEFT JOIN meter m ON news.id = m.news_id
            ORDER BY length ASC) res GROUP BY id;
        ");
    }

    /**
     * @param array $newsDtos
     * @throws AvocadoModelException
     * @throws ReflectionException
     */
    public function sortByLengthDesc(array &$newsDtos): void {
        foreach ($newsDtos as $dto) {
            usort($dto->news, function (NewsSnippet $a, NewsSnippet $b) {
                /** @var Meter[] $aMeters */
                $aMeters = $this->metersRepository->findMany(["news_id" => $a->id]);
                /** @var Meter[] $bMeters */
                $bMeters = $this->metersRepository->findMany(["news_id" => $b->id]);

                if (empty($aMeters) || empty($bMeters)) {
                    return -1;
                }

                return ($bMeters[0]->getTripLength() - $aMeters[0]->getTripLength()) ?? -1;
            });
        }
    }

    /**
     * @param array $newsDtos
     * @throws AvocadoModelException
     * @throws ReflectionException
     */
    public function sortByTimeAsc(array &$newsDtos): void {
        foreach ($newsDtos as $dto) {
            usort($dto->news, function (NewsSnippet $a, NewsSnippet $b) {
                /** @var Meter[] $aMeters */
                $aMeters = $this->metersRepository->findMany(["news_id" => $a->id]);
                /** @var Meter[] $bMeters */
                $bMeters = $this->metersRepository->findMany(["news_id" => $b->id]);

                if (empty($aMeters) || empty($bMeters)) {
                    return -1;
                }

                return ($aMeters[0]->getTime() - $bMeters[0]->getTime()) ?? -1;
            });
        }
    }

    /**
     * @param array $newsDtos
     * @throws AvocadoModelException
     * @throws ReflectionException
     */
    public function sortByTimeDesc(array &$newsDtos): void {
        foreach ($newsDtos as $dto) {
            usort($dto->news, function (NewsSnippet $a, NewsSnippet $b) {
                /** @var Meter[] $aMeters */
                $aMeters = $this->metersRepository->findMany(["news_id" => $a->id]);
                /** @var Meter[] $bMeters */
                $bMeters = $this->metersRepository->findMany(["news_id" => $b->id]);

                if (empty($aMeters) || empty($bMeters)) {
                    return -1;
                }

                return ($bMeters[0]->getTime() - $aMeters[0]->getTime()) ?? -1;
            });
        }
    }
}