<?php

namespace Hubert\BikeBlog\Controllers;

use Avocado\Application\RestController;
use Avocado\AvocadoApplication\Attributes\Request\RequestBody;
use Avocado\ORM\AvocadoModelException;
use Avocado\ORM\AvocadoRepository;
use Avocado\ORM\AvocadoRepositoryException;
use Avocado\Router\HttpRequest;
use Avocado\Tests\Unit\Application\RequestParam;
use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Attributes\BaseURL;
use AvocadoApplication\Mappings\GetMapping;
use AvocadoApplication\Mappings\PatchMapping;
use AvocadoApplication\Mappings\PostMapping;
use Hubert\BikeBlog\Exceptions\InvalidRequestException;
use Hubert\BikeBlog\Exceptions\NewsNotFoundException;
use Hubert\BikeBlog\Helpers\LoggerHelper;
use Hubert\BikeBlog\Models\DTO\MeterDTO;
use Hubert\BikeBlog\Models\Meter\Meter;
use Hubert\BikeBlog\Models\Meter\NewMeterDto;
use Hubert\BikeBlog\Utils\Validators\MetersRequestValidators;
use ReflectionException;

#[RestController]
#[BaseURL("/api/v1/meters")]
class MetersController {

    #[Autowired("metersRepository")]
    private AvocadoRepository $metersRepository;
    #[Autowired("newsRepository")]
    private AvocadoRepository $newsRepository;
    #[Autowired]
    private LoggerHelper $logger;

    /**
     * @throws InvalidRequestException
     * @throws NewsNotFoundException
     * @throws AvocadoModelException
     * @throws ReflectionException
     */
    #[GetMapping("/:newsId")]
    public function getMetersByNewsId(HttpRequest $request, #[RequestParam(name: "newsId", required: true)] string $newsId): array {
        MetersRequestValidators::validateGetMetersByNewsIdRequest($request);

        $news = $this->newsRepository->findById($newsId);

        if (!$news) {
            $exp = new NewsNotFoundException("News with id $newsId not found.");
            $this->logger->logException($request, $exp);

            throw $exp;
        }

        $meters = $this->metersRepository->findMany(["news_id" => $newsId]);

        return MeterDTO::fromArray($meters);
    }

    /**
     * @param HttpRequest $request
     * @param NewMeterDto $meterDto
     * @return array
     * @throws AvocadoModelException
     * @throws AvocadoRepositoryException
     * @throws InvalidRequestException
     * @throws ReflectionException
     */
    #[PostMapping("/")]
    public function addNewMeter(HttpRequest $request, #[RequestBody] NewMeterDto $meterDto): array {
        MetersRequestValidators::validateNewMeterRequest($request);

        $newsId = $meterDto->getNewsId();

        if (!$this->newsRepository->findById($newsId)) {
            $exp = new InvalidRequestException("Cannot create meter for news. News with id $newsId not exists.");
            $this->logger->logException($request, $exp);

            throw $exp;
        }

        $meter = Meter::from($meterDto);
        $this->metersRepository->save($meter);

        return ["message" => "Success"];
    }


    /**
     * @throws ReflectionException
     * @throws AvocadoModelException
     * @throws InvalidRequestException
     */
    #[PatchMapping("/:id")]
    public function updateMeterById(HttpRequest $request, #[RequestBody] NewMeterDto $newMeterDto): array {
        MetersRequestValidators::validateUpdateMeterRequest($request);

        $meter = Meter::from($newMeterDto);

        $this->metersRepository->updateById(["max_speed" => $meter->getMaxSpeed(), "meter_start_state" => $meter->getStartState(), "meter_end_state" => $meter->getEndState(), "trip_length" => $meter->getTripLength(), "time" => $meter->getTime(), "isToShow" => $meter->isToShow(), "newsId" => $meter->getNewsId(),],
            $meter->getId());

        return ["message" => "updated"];
    }
}
