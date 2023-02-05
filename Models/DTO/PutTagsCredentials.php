<?php

namespace Hubert\BikeBlog\Models\DTO;

class PutTagsCredentials {
    public function __construct(private readonly array $tags) {}

    public function getTags(): array {
        return $this->tags;
    }
}