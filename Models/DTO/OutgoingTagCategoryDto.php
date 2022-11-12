<?php

namespace Hubert\BikeBlog\Models\DTO;

use Hubert\BikeBlog\Models\TagCategory;

class OutgoingTagCategoryDto {

    public function __construct(public readonly string $id, public readonly string $category) {
    }

    public static function from(TagCategory $tagCategory): OutgoingTagCategoryDto {
        return new OutgoingTagCategoryDto($tagCategory->getId(), $tagCategory->getCategory());
    }
}
