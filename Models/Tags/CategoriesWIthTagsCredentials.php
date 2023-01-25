<?php

namespace Hubert\BikeBlog\Models\Tags;

class CategoriesWIthTagsCredentials {

    public function __construct(private TagCategory $category, private array $tags) {}

}