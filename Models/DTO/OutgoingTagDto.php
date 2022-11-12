<?php

namespace Hubert\BikeBlog\Models\DTO;

use Hubert\BikeBlog\Models\Tag;
use Hubert\BikeBlog\Models\TagCategory;

class OutgoingTagDto {

    public function __construct(public readonly string $id, public readonly string $tag, public readonly string $descriptor, public readonly OutgoingTagCategoryDto $category) {
    }

    public static function from(Tag $tag, TagCategory $tagCategory): OutgoingTagDto {
        return new OutgoingTagDto($tag->getId(), $tag->getTag(), $tag->getDescriptor(), OutgoingTagCategoryDto::from($tagCategory));
    }
}