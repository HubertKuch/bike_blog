<?php

namespace Hubert\BikeBlog\Models\DTO;

use Carbon\Carbon;

class NewsByYearDTO {

    /**
     * @param NewsDTO[] $news
     */
    public function __construct(public int $year, public array $news,) {
    }

    /**
     * @param NewsDTO[] $news
     * @return NewsByYearDTO[]
     * */
    public static function fromArray(array &$news): array {
        usort($news, function ($a, $b) {
            $aDate = Carbon::createFromFormat("Y-m-d", $a->time)->timestamp;
            $bDate = Carbon::createFromFormat("Y-m-d", $b->time)->timestamp;

            return $aDate - $bDate;
        });

        /** @var NewsByYearDTO[] $yearsDTOs */
        $yearsDTOs = [];

        foreach ($news as &$newsDTO) {
            $newsDateYear = intval(explode("-", $newsDTO->time)[0]);
            $yearDTOIfExists = array_filter($yearsDTOs, function ($yearDTO) use ($newsDTO, $newsDateYear) {
                return $yearDTO->year == $newsDateYear;
            });

            if (empty($yearDTOIfExists)) {
                $yearsDTOs[] = new NewsByYearDTO($newsDateYear, [$newsDTO]);
                continue;
            }

            $yearDTOIfExists[key($yearDTOIfExists)]->news[] = $newsDTO;
        }

        usort($yearsDTOs, fn($a, $b) => $b->year - $a->year);

        return $yearsDTOs;
    }
}
