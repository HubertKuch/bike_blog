<?php

namespace Hubert\BikeBlog\Models\Tags;

use Avocado\ORM\Attributes\Field;
use Avocado\ORM\Attributes\Id;
use Avocado\ORM\Attributes\Table;
use Ramsey\Uuid\Uuid;

#[Table("tags_categories")]
class TagCategory {

    #[Id]
    private string $id;
    #[Field]
    private string $category;

    public function __construct(string $category) {
        $this->id = Uuid::uuid4()->toString();
        $this->category = $category;
    }

    public function getId(): string {
        return $this->id;
    }

    public function getCategory(): string {
        return $this->category;
    }
}