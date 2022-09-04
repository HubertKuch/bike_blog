<?php

namespace Hubert\BikeBlog\Models;

class Tag {

    public function __construct(private readonly string $tag) {
    }

    public function getTag(): string {
        return $this->tag;
    }
}
