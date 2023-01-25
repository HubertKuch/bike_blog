<?php

namespace Hubert\BikeBlog\Utils;


use AvocadoApplication\Attributes\Autowired;
use AvocadoApplication\Attributes\Resource;
use Handlebars\Handlebars;
use Handlebars\Loader\FilesystemLoader;
use Hubert\BikeBlog\Configuration\FrontEndConfiguration;
use Hubert\BikeBlog\Controllers\NewsController;
use Hubert\BikeBlog\Models\DTO\NewsDTO;
use Hubert\BikeBlog\Models\Image\ImagesDTO;
use Hubert\BikeBlog\Models\News\News;

#[Resource]
class Views {

    private Handlebars $handlebars;
    #[Autowired]
    private FrontEndConfiguration $frontEndConfiguration;
    #[Autowired]
    private NewsController $newsController;

    public function __construct() {
        $templatesDir = "Client/views";
        $loader = new FilesystemLoader($templatesDir, ["extension" => "html"]);

        $this->handlebars = new Handlebars(["loader" => $loader, "partials_loader" => $loader,]);
    }

    public function main(): void {
        echo @$this->handlebars->render("main", $this->getModelData());
    }

    private function getModelData(array $data = []): array {
        $static = ["base_url" => $this->frontEndConfiguration->getApiBaseURl()];

        return array_merge($static, $data);
    }

    public function news(): void {
        $allNews = $this->newsController->getAllNews();
        $allNewsJsonData = json_encode($allNews);
        $allNewsJsonData = str_replace('"', '\\"', $allNewsJsonData);

        $id = $_GET['id'] ?? $allNews[0]->news[0]->id;
        $actualNewsJsonData = json_encode($this->newsController->getNewsById($id));
        $actualNewsJsonData = str_replace('"', '\\"', $actualNewsJsonData);
        $actualNewsJsonData = str_replace("\\n", '', $actualNewsJsonData);
        $actualNewsJsonData = str_replace("\\r", '', $actualNewsJsonData);

        echo @$this->handlebars->render("news", $this->getModelData(["news" => $allNewsJsonData, "actual_news" => $actualNewsJsonData]));
    }

    public function admin(): void {
        echo @$this->handlebars->render("admin", $this->getModelData(["username" => $_SESSION['user']->getUsername()]));
    }

    public function login(): void {
        echo @$this->handlebars->render("login", $this->getModelData());
    }

    public function editNews(News $news): void {
        echo @$this->handlebars->render("editNews", $this->getModelData(["news" => json_encode(NewsDTO::from($news))]));
    }

    public function newNews(): void {
        echo @$this->handlebars->render("editNews", $this->getModelData());
    }

    public function tags(): void {
        echo @$this->handlebars->render("tags", $this->getModelData());
    }

    public function images(array $news, array $images): void {
        echo @$this->handlebars->render("images",
            $this->getModelData(["news" => "" . json_encode(NewsDTO::fromArray($news)), "images" => "" . json_encode(ImagesDTO::fromArray($images))]));
    }

    public function contact(): void {
        echo @$this->handlebars->render("contact", $this->getModelData());
    }
}
