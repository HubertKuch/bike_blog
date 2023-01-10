<?php

namespace Hubert\BikeBlog\Models\DTO;

class NewsSnippet {


    public function __construct(public string $id, public string $title, public string $time) {}

}