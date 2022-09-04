<?php

namespace Hubert\BikeBlog\Utils;

class Views {

    private const VIEWS_DIR = "Client/views/";
    private const COMPONENTS_DIR = "Client/views/components/";

    public static function main(): void {
        echo self::getViewContent("main.html");
    }

    private static function getViewContent(string $view): string {
        $content = file_get_contents(self::VIEWS_DIR . $view);

        $content = self::prepare($content);

        return $content;
    }

    private static function prepare(string $viewContent): string {
        $viewContent = str_replace("<!-- __HEAD__ -->", self::getComponent("head.html"), $viewContent);
        $viewContent = str_replace("<!-- __HEADER__ -->", self::getComponent("header.html"), $viewContent);
        $viewContent = str_replace("<!-- __FOOTER__ -->", self::getComponent("footer.html"), $viewContent);
        $viewContent = str_replace("<!-- __NAV__-->", self::getComponent("nav.html"), $viewContent);
        $viewContent = str_replace("<!-- __BANNER_IN_MAIN__ -->", self::getComponent("banner_in_main.html"), $viewContent);

        return $viewContent;
    }

    private static function getComponent(string $component): string {
        return file_get_contents(self::COMPONENTS_DIR . $component);
    }

    public static function news(): void {
        echo self::getViewContent("news.html");
    }
}
