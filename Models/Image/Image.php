<?php

namespace Hubert\BikeBlog\Models\Image;

use Avocado\ORM\Attributes\Field;
use Avocado\ORM\Attributes\Id;
use Avocado\ORM\Attributes\Table;
use Ramsey\Uuid\Uuid;

#[Table("images")]
class Image {

    public function __construct(#[Id]
                                private readonly string $id, #[Field]
                                private readonly string $name, #[Field("news_id")]
                                private readonly string $newsId) {
    }

    public static function from(string $name, string $newsId): Image {
        return new Image(Uuid::uuid4()->toString(), $name, $newsId);
    }

    public function getId(): string {
        return $this->id;
    }

    public function getName(): string {
        return $this->name;
    }

    public function getNewsId(): string {
        return $this->newsId;
    }
}