<?php

namespace Hubert\BikeBlog\Utils;


use AvocadoApplication\Attributes\Resource;
use Handlebars\Handlebars;
use Handlebars\Loader\FilesystemLoader;
use Hubert\BikeBlog\Models\DTO\NewsDTO;
use Hubert\BikeBlog\Models\News;

#[Resource]
class Views {

    private Handlebars $handlebars;

    public function __construct() {
        $templatesDir = "Client/views";
        $loader = new FilesystemLoader($templatesDir, ["extension" => "html"]);

        $this->handlebars = new Handlebars([
            "loader"          => $loader,
            "partials_loader" => $loader,
        ]);
    }

    public function main(): void {
        echo @$this->handlebars->render("main", []);
    }

    public function news(): void {
        echo @$this->handlebars->render("news", []);
    }

    public function admin(): void {
        echo @$this->handlebars->render("admin", [
            "username" => $_SESSION['user']->getUsername()
        ]);
    }

    public function login(): void {
        echo @$this->handlebars->render("login", []);
    }

    public function editNews(News $news): void {
        echo @$this->handlebars->render("editNews", [
            "news" => json_encode(NewsDTO::from($news))
        ]);
    }

    public function newNews(): void {
        echo @$this->handlebars->render("editNews", []);
    }
}
