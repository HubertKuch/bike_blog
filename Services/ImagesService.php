<?php

namespace Hubert\BikeBlog\Services;

use Avocado\AvocadoApplication\Files\MultipartFile;
use Avocado\ORM\AvocadoRepository;
use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Attributes\Resource;
use Hubert\BikeBlog\Models\Image\Image;

#[Resource]
class ImagesService {

    #[Autowired("imagesRepository")]
    private readonly AvocadoRepository $imageRepository;

    public function __construct() {
    }

    /**
     * @return Image[]
     * */
    public function getNewsImages(string $newsId): array {
        return $this->imageRepository->findMany(["news_id" => $newsId]);
    }

    public function saveImage(MultipartFile $multipartFile, string $newsId): void {
        $image = Image::from($multipartFile->getName(), $newsId);

        $this->imageRepository->save($image);
    }
}