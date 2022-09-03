<?php

namespace Hubert\BikeBlog\Controllers;

use Carbon\Carbon;
use ReflectionException;
use Avocado\HTTP\HTTPStatus;
use Hubert\BikeBlog\Models\News;
use Avocado\ORM\AvocadoRepository;
use Avocado\Router\AvocadoRequest;
use Avocado\Router\AvocadoResponse;
use Hubert\BikeBlog\Models\NewsDTO;
use Avocado\ORM\AvocadoModelException;
use Avocado\Application\RestController;
use Hubert\BikeBlog\Models\NewsByYearDTO;
use AvocadoApplication\Attributes\BaseURL;
use AvocadoApplication\Mappings\GetMapping;
use Avocado\ORM\AvocadoRepositoryException;
use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Mappings\PostMapping;
use Hubert\BikeBlog\Exceptions\InvalidRequest;
use Hubert\BikeBlog\Utils\Validators\NewsValidator;
use Hubert\BikeBlog\Exceptions\NewsNotFoundException;

#[RestController]
#[BaseURL("/api/")]
class NewsController {

    #[Autowired("newsRepository")]
    private AvocadoRepository $newsRepository;

    /**
     * @throws AvocadoModelException
     * @throws ReflectionException
     * @throws AvocadoRepositoryException
     * @throws InvalidRequest
     */
    #[PostMapping("/v1/news/")]
    public function newNews(AvocadoRequest $request, AvocadoResponse $response): AvocadoResponse {
        NewsValidator::validateNewNews($request);

        $news = News::from($request);

        $this->newsRepository->save($news);

        return $response->withStatus(HTTPStatus::CREATED)->json(["message" => "Success"]);
    }

    /**
     * @throws AvocadoModelException
     * @throws ReflectionException
     */
    #[GetMapping("/v2/news/")]
    public function getAllNews(AvocadoRequest $request, AvocadoResponse $response): AvocadoResponse {
        $news = $this->newsRepository->findMany();

        $newsDTOs = array_map(fn($n) => NewsDTO::from($n), $news);
        $newsByYearDTOs = $this->groupNewsDTOsByYearToDTO($newsDTOs);

        return $response->json($newsByYearDTOs);
    }

    /**
     * @param NewsDTO[] $news
     * @return NewsByYearDTO[]
     * */
    private function groupNewsDTOsByYearToDTO(array &$news): array {
        usort($news, function ($a, $b) {
            $aDate = Carbon::createFromFormat("Y-m-d", $a->time)->timestamp;
            $bDate = Carbon::createFromFormat("Y-m-d", $b->time)->timestamp;

            return $aDate - $bDate;
        });

        /** @var NewsByYearDTO[] $yearsDTOs */
        $yearsDTOs = [];

        foreach ($news as &$newsDTO) {
            $newsDateYear = intval(explode("-", $newsDTO->time)[0]);
            $yearDTOIfExists = array_filter($yearsDTOs, function ($yearDTO) use ($newsDTO, $newsDateYear) {
                return $yearDTO->year == $newsDateYear;
            });

            if (empty($yearDTOIfExists)) {
                $yearsDTOs[] = new NewsByYearDTO($newsDateYear, [$newsDTO]);
                continue;
            }

            $yearDTOIfExists[key($yearDTOIfExists)]->news[] = $newsDTO;
        }

        usort($yearsDTOs, fn($a, $b) => $b->year - $a->year);

        return $yearsDTOs;
    }

    /**
     * @throws ReflectionException
     * @throws AvocadoModelException
     * @throws NewsNotFoundException
     */
    #[GetMapping("/v1/news/:id")]
    public function getNewsById(AvocadoRequest $request, AvocadoResponse $response): AvocadoResponse {
        $id = $request->params['id'];

        $news = $this->newsRepository->findById($id);

        if (!$news) {
            throw new NewsNotFoundException("News with id $id not found.");
        }

        return $response->json([NewsDTO::from($news)]);
    }

    #[GetMapping("/v1/news/tag/:tag")]
    public function getNewsByTag(AvocadoRequest $request, AvocadoResponse $response): AvocadoResponse {
        NewsValidator::validateFindByTag($request);

        $tag = $request->params['tag'];

        $news = $this->newsRepository->findMany(["tags" => "%$tag%"]);

        $newsDTOs = array_map(fn($n) => NewsDTO::from($n), $news);

        return $response->withStatus(HTTPStatus::OK)->json($newsDTOs);
    }
}
