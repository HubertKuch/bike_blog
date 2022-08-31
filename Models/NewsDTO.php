<?php

namespace Hubert\BikeBlog\Models;

class NewsDTO {

    public function __construct(public readonly string $id, public readonly string $title, public readonly string $description, public readonly array $tags, public readonly string $time) {
    }

    public static function from(News $news): NewsDTO {
        return new NewsDTO($news->getId(), $news->getTitle(), $news->getDescription(), array_map(fn($tag) => $tag->getTag(), $news->getTags()), $news->getDate()->toDateString());
    }
}
