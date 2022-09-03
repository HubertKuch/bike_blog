<?php

namespace Hubert\BikeBlog\Models\DTO;

class NewsByYearDTO {

    /**
     * @param NewsDTO[] $news
     */
    public function __construct(public int $year, public array $news,) {
    }
}
