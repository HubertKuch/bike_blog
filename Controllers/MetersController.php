<?php

namespace Hubert\BikeBlog\Controllers;

use Avocado\HTTP\HTTPStatus;
use Avocado\ORM\AvocadoModelException;
use Avocado\ORM\AvocadoRepositoryException;
use AvocadoApplication\Mappings\PatchMapping;
use Hubert\BikeBlog\Models\Meter;
use Avocado\Router\AvocadoRequest;
use Avocado\ORM\AvocadoRepository;
use Avocado\Router\AvocadoResponse;
use Avocado\Application\RestController;
use Hubert\BikeBlog\Models\DTO\MeterDTO;
use Hubert\BikeBlog\Helpers\LoggerHelper;
use AvocadoApplication\Attributes\BaseURL;
use AvocadoApplication\Mappings\GetMapping;
use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Mappings\PostMapping;
use Hubert\BikeBlog\Exceptions\InvalidRequest;
use Hubert\BikeBlog\Exceptions\NewsNotFoundException;
use Hubert\BikeBlog\Utils\Validators\MetersRequestValidators;
use ReflectionException;

#[RestController]
#[BaseURL("/api/v1/meters")]
class MetersController
{

    #[Autowired("metersRepository")]
    private AvocadoRepository $metersRepository;
    #[Autowired("newsRepository")]
    private AvocadoRepository $newsRepository;
    #[Autowired]
    private LoggerHelper $logger;

    /**
     * @param AvocadoRequest $request
     * @param AvocadoResponse $response
     * @return AvocadoResponse
     * @throws InvalidRequest
     * @throws NewsNotFoundException
     * @throws AvocadoModelException
     * @throws ReflectionException
     */
    #[GetMapping("/:newsId")]
    public function getMetersByNewsId(AvocadoRequest $request, AvocadoResponse $response): AvocadoResponse
    {
        $this->logger->logRequest($request);
        MetersRequestValidators::validateGetMetersByNewsIdRequest($request);

        $newsId = $request->params['newsId'];
        $news = $this->newsRepository->findById($newsId);

        if (!$news) {
            $exp = new NewsNotFoundException("News with id $newsId not found.");
            $this->logger->logException($request, $exp);

            throw $exp;
        }

        $meters = $this->metersRepository->findMany(["news_id" => $newsId]);

        return $response->json(MeterDTO::fromArray($meters))->withStatus(HTTPStatus::OK);
    }

    /**
     * @param AvocadoRequest $request
     * @param AvocadoResponse $response
     * @return AvocadoResponse
     * @throws AvocadoModelException
     * @throws InvalidRequest
     * @throws ReflectionException
     * @throws AvocadoRepositoryException
     */
    #[PostMapping("/")]
    public function addNewMeter(AvocadoRequest $request, AvocadoResponse $response): AvocadoResponse
    {
        $this->logger->logRequest($request);
        MetersRequestValidators::validateNewMeterRequest($request);

        $newsId = $request->body['newsId'];

        if (!$this->newsRepository->findById($newsId)) {
            $exp = new InvalidRequest("Cannot create meter for news. News with id $newsId not exists.");
            $this->logger->logException($request, $exp);

            throw $exp;
        }

        $meter = Meter::fromRequest($request);
        $this->metersRepository->save($meter);

        return $response->withStatus(HTTPStatus::CREATED)->json(["message" => "Success"]);
    }


    /**
     * @param AvocadoRequest $request
     * @param AvocadoResponse $response
     * @return AvocadoResponse
     * @throws AvocadoModelException
     * @throws InvalidRequest
     * @throws ReflectionException
     */
    #[PatchMapping("/:id")]
    public function updateMeterById(AvocadoRequest $request, AvocadoResponse $response): AvocadoResponse
    {
        $this->logger->logRequest($request);
        MetersRequestValidators::validateUpdateMeterRequest($request);

        $meter = Meter::fromRequest($request);

        $this->metersRepository->updateById([
            "max_speed" => $meter->getMaxSpeed(),
            "meter_start_state" => $meter->getStartState(),
            "meter_end_state" => $meter->getEndState(),
            "trip_length" => $meter->getTripLength(),
            "time" => $meter->getTime(),
            "isToShow" => $meter->isToShow(),
            "newsId" => $meter->getNewsId(),
        ], $meter->getId());

        return $response->withStatus(HTTPStatus::ACCEPTED)->json(["message" => "updated"]);
    }
}
