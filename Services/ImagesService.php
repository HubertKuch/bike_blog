<?php

namespace Hubert\BikeBlog\Services;

use Avocado\ORM\AvocadoRepository;
use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Attributes\Resource;
use Hubert\BikeBlog\Configuration\ImagesConfiguration;
use Hubert\BikeBlog\Models\Image\Image;
use Hubert\BikeBlog\Models\News\News;

#[Resource]
class ImagesService {

    #[Autowired]
    private ImagesConfiguration $imagesConfiguration;
    #[Autowired("imagesRepository")]
    private AvocadoRepository $imageRepository;

    public function __construct() {
    }

    /**
     * @return string[]
     * */
    public function getNewsImagesFullPaths(News $news): array {
        $root = $this->imagesConfiguration->getRoot() . $news->getId();

        /** @var $images Image[] */
        $images = $this->imageRepository->findMany(["news_id" => $news->getId()]);

        return array_map(fn($image) => $root . $image->getName(), $images);
    }

    /**
     * @return Image[]
     * */
    public function getNewsImages(News $news): array {
        $root = $this->imagesConfiguration->getRoot() . $news->getId();

        return $this->imageRepository->findMany(["news_id" => $news->getId()]);
    }

}