<?php

namespace Hubert\BikeBlog\Controllers;

use Avocado\Application\RestController;
use Avocado\AvocadoApplication\Attributes\Request\Multipart;
use Avocado\AvocadoApplication\Files\Exceptions\CannotMoveFileException;
use Avocado\AvocadoApplication\Files\MultipartFile;
use Avocado\HTTP\HTTPStatus;
use Avocado\HTTP\ResponseBody;
use Avocado\Tests\Unit\Application\RequestParam;
use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Attributes\BaseURL;
use AvocadoApplication\Mappings\DeleteMapping;
use AvocadoApplication\Mappings\GetMapping;
use AvocadoApplication\Mappings\PostMapping;
use Hubert\BikeBlog\Configuration\ImagesConfiguration;
use Hubert\BikeBlog\Exceptions\InvalidRequestException;
use Hubert\BikeBlog\Models\Image\ImagesDTO;
use Hubert\BikeBlog\Services\ImagesService;
use Hubert\BikeBlog\Storage\StaticStorage;

#[RestController]
#[BaseURL("/api/v1/images")]
class ImagesController {

    #[Autowired]
    private readonly ImagesService $imagesService;
    #[Autowired]
    private readonly StaticStorage $storage;
    #[Autowired]
    private readonly ImagesConfiguration $imagesConfiguration;

    #[GetMapping("/news/:newsId")]
    public function getImagesByNewsId(#[RequestParam(name: "newsId", required: true)] string $newsId) {
        return ImagesDTO::fromArray($this->imagesService->getNewsImages($newsId));
    }

    /** @param MultipartFile[] $files */
    #[PostMapping("/news/:newsId")]
    public function uploadPhotos(#[Multipart(name: "images")] array $files, #[RequestParam(name: "newsId", required: true)] string $newsId): ResponseBody {
        foreach ($files as $file) {
            $this->storage->store($file, $newsId);

            $this->imagesService->saveImage($file, $newsId);
        }

        return new ResponseBody(["message" => "Uploaded",], HTTPStatus::OK);
    }

    /**
     * @throws InvalidRequestException
     * @throws CannotMoveFileException
     */
    #[DeleteMapping("/news/:newsId/image/:imageId")]
    public function deleteImage(#[RequestParam(name: "newsId", required: true)] string $newsId, #[RequestParam(name: "imageId", required: true)] string $imageId): ResponseBody {
        $image = $this->imagesService->getImageById($imageId);

        if(!$image) {
            throw new InvalidRequestException("Invalid imageId param");
        }

        $absPath = $this->imagesConfiguration->getRoot() . $newsId . "/" . $image->getName();

        $removed = $this->storage->remove($absPath);

        if(!$removed) {
            throw new CannotMoveFileException("Cannot remove image");
        }

        $this->imagesService->removeImage($imageId);

        return new ResponseBody(["message" => "Removed successfully"], HTTPStatus::OK);
    }

}