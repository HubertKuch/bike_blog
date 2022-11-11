<?php

$db = new PDO("mysql:host=127.0.0.1;dbname=bike_blog;port=3306", "root", "");

$stmt = $db->query("SELECT id, tagi FROM bike_blog.newsy");
$stmt->execute();

$data = $stmt->fetchAll(PDO::FETCH_CLASS);
$tags = array_map(fn($news) => explode(";", $news->tagi), $data);
$tags = array_merge_recursive(...$tags);
$tags = array_unique($tags);

//foreach ($tags as $key => $tag) {
//    $db->query("INSERT INTO bike_blog.tags values(uuid(), '$tag', 'unnamed_descriptor', (SELECT id FROM bike_blog.tags_categories LIMIT 1))");
//}

$tagsFromDbStmt = $db->prepare("SELECT id, tag FROM bike_blog.tags");
$tagsFromDbStmt->execute();
$tagsFromDb = $tagsFromDbStmt->fetchAll(PDO::FETCH_CLASS);


foreach ($data as $oldNew) {
    foreach ($tagsFromDb as $tag) {
        if(strlen($tag->tag) == 0 || strlen($oldNew->tagi) == 0) {
            continue;
        }

        if(str_contains($oldNew->tagi, $tag->tag)) {
            $newsIdStmt = $db->prepare("SELECT news.id FROM bike_blog.news WHERE news.title IN (SELECT title FROM bike_blog.newsy WHERE bike_blog.newsy.id = $oldNew->id)");
            $newsIdStmt->execute();

            $newsId = $newsIdStmt->fetchAll(PDO::FETCH_CLASS);

            $newsTagStmt = $db->prepare("INSERT INTO bike_blog.news_tag VALUE (uuid(), '{$newsId[key($newsId)]->id}', '{$tag->id}')");
            $newsTagStmt->execute();
        }
    }
}
