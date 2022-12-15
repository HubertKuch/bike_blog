<?php

namespace Hubert\BikeBlog\Models\DTO;

use Hubert\BikeBlog\Models\News\News;

class NewsDTO {

    public function __construct(
        public readonly string $id,
        public readonly string $title,
        public readonly string $description,
        public readonly string $time
    ) {
    }

    /**
     * @param News[] $news
     * @return NewsDTO[]
     * */
    public static function fromArray(array $news): array {
        return array_map(fn($n) => NewsDTO::from($n), $news);
    }

    public static function from(News $news): NewsDTO {
        return new NewsDTO(
            $news->getId(),
            $news->getTitle(),
            $news->getDescription(),
            $news->getDate()->toDateString());
    }
}
