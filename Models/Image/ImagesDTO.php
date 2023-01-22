<?php

namespace Hubert\BikeBlog\Models\Image;

class ImagesDTO {

    public function __construct(public readonly string $id, public readonly string $name, public readonly string $newsId) {
    }

    /**
     * @param Image[] $images
     * @return ImagesDTO[]
     */
    public static function fromArray(array $images): array {
        return array_map(fn($image) => self::from($image), $images);
    }

    public static function from(Image $image): ImagesDTO {
        return new ImagesDTO($image->getId(), $image->getName(), $image->getNewsId());
    }

}