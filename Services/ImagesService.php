<?php

namespace Hubert\BikeBlog\Services;

use Avocado\ORM\AvocadoRepository;
use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Attributes\Resource;
use Hubert\BikeBlog\Models\Image\Image;
use Hubert\BikeBlog\Models\News\News;

#[Resource]
class ImagesService {

    #[Autowired("imagesRepository")]
    private readonly AvocadoRepository $imageRepository;

    public function __construct() {
    }

    /**
     * @return Image[]
     * */
    public function getNewsImages(News $news): array {
        return $this->imageRepository->findMany(["news_id" => $news->getId()]);
    }
}