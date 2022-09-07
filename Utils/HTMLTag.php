<?php

namespace Hubert\BikeBlog\Utils;

class HTMLTag {

    public function __construct(
        private string $tag,
    ) {
    }

    public function getTag(): string {
        return $this->tag;
    }

    public function __toString(): string {
        return $this->getHTML();
    }

    public function getHTML(): string {
        return "<$this->tag/>";
    }
}
