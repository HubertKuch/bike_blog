<?php

namespace Hubert\BikeBlog\Storage;

use Avocado\AvocadoApplication\Files\Exceptions\CannotMoveFileException;
use Avocado\AvocadoApplication\Files\MultipartFile;
use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Attributes\Resource;
use Exception;
use Hubert\BikeBlog\Configuration\ImagesConfiguration;
use Throwable;

#[Resource]
class StaticStorageImpl implements StaticStorage {

    #[Autowired]
    private readonly ImagesConfiguration $imagesConfiguration;

    public function __construct() {
    }

    /**
     * @throws CannotMoveFileException
     */
    public function store(MultipartFile $multipartFile, string $newsId): void {
        try {
            if(str_starts_with($multipartFile->getMime()->value, "image/")) {
                $folderPath = $this->imagesConfiguration->getRoot() . $newsId;

                if(!is_dir($folderPath)) {
                    mkdir($folderPath);
                }

                $filenamePath = $folderPath . "/" . $multipartFile->getName();

                $multipartFile->moveTo($filenamePath);
            }
        } catch (Throwable $throwable) {
            throw new CannotMoveFileException("Cannot move {$multipartFile->getName()} file.", 1, $throwable);
        }
    }

    public function remove(string $absolutePath): bool {
        try {
            return unlink($absolutePath);
        } catch (Exception) {
            return false;
        }
    }
}