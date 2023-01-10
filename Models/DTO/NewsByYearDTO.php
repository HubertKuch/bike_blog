<?php

namespace Hubert\BikeBlog\Models\DTO;

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
            $aDate = strtotime($a->time);
            $bDate = strtotime($b->time);

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
