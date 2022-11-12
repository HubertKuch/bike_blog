<?php

$db = new PDO("mysql:host=127.0.0.1;dbname=bike_blog;port=3306", "root", "");

$stmt = $db->query("SELECT id, tagi, tytul FROM bike_blog.newsy");
$stmt->execute();

$data = $stmt->fetchAll(PDO::FETCH_CLASS);
$tags = array_map(fn($news) => explode(";", $news->tagi), $data);
$tags = array_merge_recursive(...$tags);
$tags = array_unique($tags);

foreach ($tags as $key => $tag) {
    $db->query("INSERT INTO bike_blog.tags values(uuid(), '$tag', 'unnamed_descriptor', (SELECT id FROM bike_blog.tags_categories LIMIT 1))");
}

$tagsFromDbStmt = $db->prepare("SELECT id, tag FROM bike_blog.tags");
$tagsFromDbStmt->execute();
$tagsFromDb = $tagsFromDbStmt->fetchAll(PDO::FETCH_CLASS);

foreach ($tagsFromDb as $tag) {
    foreach ($data as $news) {
        if(str_contains($news->tagi, $tag->tag)) {
            try {
                $newsIdStmt = $db->prepare("SELECT news.id FROM bike_blog.news WHERE bike_blog.news.title LIKE '{$news->tytul}'");
                $newsIdStmt->execute();
                $newsId = $newsIdStmt->fetchAll(PDO::FETCH_CLASS);

                if(key($newsId) == null && key($newsId) != 0) {
                    var_dump(key($newsId));
                    continue;
                }

                $newsId = $newsId[key($newsId)]->id;

                if(!$newsId) {
                    continue;
                }

                $newsTagStmt = $db->prepare("INSERT INTO bike_blog.news_tag VALUE (uuid(), '{$newsId}', '{$tag->id}')");
                $newsTagStmt->execute();
            } catch (Error) {
            }
        }
    }
}
