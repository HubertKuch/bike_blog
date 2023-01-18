<?php

error_reporting(E_CORE_ERROR);

$db = new PDO("mysql:host=172.17.0.1;dbname=bike_blog;port=3306;charset=utf8mb4", "user", "user");

$currentNewsStmt = $db->prepare("SELECT * FROM news");
$currentNewsStmt->execute();

$currentNews = $currentNewsStmt->fetchAll(PDO::FETCH_CLASS);


foreach ($currentNews as $currentNew) {
    $description = $currentNew->description;
    $doc = new DOMDocument();

    $docContent = <<<HTML
        <html lang=pl>
        <head>
        <meta charset="UTF-8">
                     <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
                                 <meta http-equiv="X-UA-Compatible" content="ie=edge">
                     <title>Document</title>
        </head>
        <body>
          $currentNew->description
        </body>
        </html>
    
    HTML;


    $doc->loadHTML($docContent);

    $anchors = $doc->getElementsByTagName("a");

    foreach ($anchors as $anchor) {
        if (!str_contains($anchor->getAttribute("href"), "newsy.php?id=")) {
            var_dump($anchor->getAttribute("href") . "    " . $anchor->textContent);
            continue;
        }

        $query = [];
        parse_str(parse_url($anchor->getAttribute("href"), PHP_URL_QUERY), $query);
        $id = $query['id'];

        $oldNewsByIdStmt = $db->prepare("SELECT * FROM newsy WHERE id = ?");
        $oldNewsByIdStmt->execute([$id]);
        $oldNews = $oldNewsByIdStmt->fetchAll(PDO::FETCH_CLASS)[0];

        $newNewsByOldNewsTitle = $db->prepare("SELECT * FROM news WHERE title = ?");
        $newNewsByOldNewsTitle->execute([$oldNews->tytul]);
        $newNews = $newNewsByOldNewsTitle->fetchAll(PDO::FETCH_CLASS)[0];

        $url = "https://wp.rowerowe.xaa.pl/news?id=" . $newNews->id;

        $anchor->setAttribute("href", $url);

        $doc->saveHTML();
    }

    $content = $doc->saveHTML($doc);
    $content = substr($content, strpos($content, "body") + 5, strlen($content));
    $content = substr($content, 0, strpos($content, "body") - 2);

    $updateNewsStmt = $db->prepare("UPDATE news SET description = ? WHERE id = ?");
    $isFinished = $updateNewsStmt->execute([$content, $currentNew->id]);

    if (!$isFinished) {
        throw new Exception("Cannot migrate.");
    }
}

echo "\x1b[32m Successfully migrated links! ";
