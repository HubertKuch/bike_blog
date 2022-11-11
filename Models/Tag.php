<?php

namespace Hubert\BikeBlog\Models;

use Avocado\ORM\Attributes\Field;
use Avocado\ORM\Attributes\Id;
use Avocado\ORM\Attributes\Table;
use Ramsey\Uuid\Uuid;

#[Table("tags")]
class Tag {
    #[Id]
    private string $id;
    #[Field]
    private string $tag;
    #[Field]
    private string $descriptor;
    #[Field]
    private string $category_id;

    public function __construct(string $tag, string $descriptor, string $category_id) {
        $this->id = Uuid::uuid4()->toString();
        $this->tag = $tag;
        $this->descriptor = $descriptor;
        $this->category_id = $category_id;
    }

    public function getId(): string {
        return $this->id;
    }

    public function getTag(): string {
        return $this->tag;
    }

    public function getDescriptor(): string {
        return $this->descriptor;
    }

    public function getCategoryId(): string {
        return $this->category_id;
    }
}
