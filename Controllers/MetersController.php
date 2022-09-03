<?php

namespace Hubert\BikeBlog\Controllers;

use Avocado\HTTP\HTTPStatus;
use Hubert\BikeBlog\Models\Meter;
use Avocado\Router\AvocadoRequest;
use Avocado\ORM\AvocadoRepository;
use Avocado\Router\AvocadoResponse;
use Avocado\Application\RestController;
use Hubert\BikeBlog\Models\DTO\MeterDTO;
use AvocadoApplication\Attributes\BaseURL;
use AvocadoApplication\Mappings\GetMapping;
use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Mappings\PostMapping;
use Hubert\BikeBlog\Exceptions\InvalidRequest;
use Hubert\BikeBlog\Exceptions\NewsNotFoundException;
use Hubert\BikeBlog\Utils\Validators\MetersValidators;

#[RestController]
#[BaseURL("/api/v1/meters")]
class MetersController {

    #[Autowired("metersRepository")]
    private AvocadoRepository $metersRepository;
    #[Autowired("newsRepository")]
    private AvocadoRepository $newsRepository;

    /**
     * @throws InvalidRequest
     * @throws NewsNotFoundException
     */
    #[GetMapping("/:newsId")]
    public function getMetersByNewsId(AvocadoRequest $request, AvocadoResponse $response): AvocadoResponse {
        MetersValidators::validateGetMetersByNewsIdRequest($request);

        $newsId = $request->params['newsId'];
        $news = $this->newsRepository->findById($newsId);

        if (!$news) {
            throw new NewsNotFoundException("News with id $newsId not found.");
        }

        $meters = $this->metersRepository->findMany(["news_id" => $newsId]);
        $metersDTOs = array_map(fn($meter) => MeterDTO::from($meter), $meters);

        return $response->json($metersDTOs)->withStatus(HTTPStatus::OK);
    }

    /**
     * @throws InvalidRequest
     * @throws NewsNotFoundException
     */
    #[PostMapping("/")]
    public function addNewMeter(AvocadoRequest $request, AvocadoResponse $response): AvocadoResponse {
        MetersValidators::validateNewMeterRequest($request);

        $newsId = $request->body['newsId'];

        if (!$this->newsRepository->findById($newsId)) {
            throw new InvalidRequest("Cannot create meter for news. News with id $newsId not exists.");
        }

        $meter = Meter::fromRequest($request);
        $this->metersRepository->save($meter);

        return $response->withStatus(HTTPStatus::CREATED)->json(["message" => "Success"]);
    }
}
