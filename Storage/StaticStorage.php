<?php

namespace Hubert\BikeBlog\Storage;

use Avocado\AvocadoApplication\Files\MultipartFile;

interface StaticStorage {

    function store(MultipartFile $multipartFile, string $newsId): void;

    function remove(string $absolutePath): bool;

}