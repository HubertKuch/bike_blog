<?php

namespace Hubert\BikeBlog\Utils;

use AvocadoApplication\Attributes\Resource;

#[Resource]
class CustomHTMLTagsParser {

    public function __construct() {
    }

    public function parseTag(HTMLTag $tag, string $to, string $content): string {
        return str_replace($tag->getHTML(), $to, $content);
    }
}
