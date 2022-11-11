<?php

namespace Hubert\BikeBlog\Models;

use Avocado\ORM\Attributes\Field;
use Avocado\ORM\Attributes\Id;
use Avocado\ORM\Attributes\Table;
use Ramsey\Uuid\Uuid;

#[Table("news_tag")]
class NewsTag {

    #[Id]
    private string $id;
    #[Field("news_id")]
    private string $newsId;
    #[Field("tag_id")]
    private string $tagId;

    public function __construct(string $newsId, string $tagId) {
        $this->id = Uuid::uuid4()->toString();
        $this->newsId = $newsId;
        $this->tagId = $tagId;
    }

    public function getId(): string {
        return $this->id;
    }

    public function getNewsId(): string {
        return $this->newsId;
    }

    public function getTagId(): string {
        return $this->tagId;
    }
}