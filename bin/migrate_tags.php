<?php

$db = require "db.php";
$jsonTags = file_get_contents(__DIR__ . "/tags_data.json");
$tagsData = json_decode($jsonTags);

function saveCategories(PDO $db, array $tagsDat): void {
    foreach ($tagsDat as $item) {
        $ifExistsStmt = $db->prepare("SELECT COUNT(id) FROM tags_categories WHERE category = ?");
        $ifExistsStmt->bindParam(1, $item->name);
        $ifExistsStmt->execute();
        $isExists = count($ifExistsStmt->fetchAll(PDO::FETCH_CLASS)) > 0;

        if (!$isExists) {
            $saveCategoryQuery = "INSERT INTO tags_categories(id, category) VALUES (UUID(), ?)";
            $saveCategoryStmt = $db->prepare($saveCategoryQuery);
            $saveCategoryStmt->bindParam(1, $item->name);

            $saveCategoryStmt->execute();
        }
    }
}


function saveRawTagsData(PDO $db, array $tagsDat): void {
    foreach ($tagsDat as $tagCategory) {
        $tagCategoryStmt = $db->prepare("SELECT id FROM tags_categories WHERE category = ?");
        $tagCategoryStmt->bindParam(1, $tagCategory->name);
        $tagCategoryStmt->execute();
        $categoryId = $tagCategoryStmt->fetchAll(PDO::FETCH_CLASS);
        $categoryId = $categoryId[key($categoryId)]->id;

        foreach ($tagCategory->tags as $tag) {
            $saveTagQuery = "INSERT INTO tags(id, tag, descriptor, category_id) VALUE(UUID(), ?, ?, ?)";
            $saveTagStmt = $db->prepare($saveTagQuery);

            $saveTagStmt->bindParam(1, $tag->tagName);
            $saveTagStmt->bindParam(2, $tag->descriptor);
            $saveTagStmt->bindParam(3, $categoryId);
            $saveTagStmt->execute();
        }
    }
}

function saveNewsTags(PDO $db, array $tags): void {
    // TODO: GET TAG BY NAME AND SAVE WITH NEWS RELATION
    $oldNews = $db->prepare("SELECT tagi, tytul FROM newsy");
    $oldNews->execute();
    $oldNews = $oldNews->fetchAll(PDO::FETCH_CLASS);

    foreach ($oldNews as $old) {
        $tags = array_filter(array_map(fn($tag) => trim($tag), array_filter(array_unique(explode(";", $old->tagi)))));

        foreach ($tags as $tag) {
            $tagId = $db->prepare("SELECT id FROM tags WHERE tag = ?");
            $tagId->bindParam(1, $tag);
            $tagId->execute();
            $tagId = $tagId->fetchAll(PDO::FETCH_CLASS);
            $tagId = $tagId[key($tagId)]->id;

            $news = $db->prepare("SELECT id FROM news WHERE title = ?");
            $news->bindParam(1, $old->tytul);
            $news->execute();
            $news = $news->fetchAll(PDO::FETCH_CLASS);
            $news = $news[key($news)];

            $isExists = $db->prepare("SELECT tag_id FROM news_tag WHERE tag_id = ? AND news_id = ?");
            $isExists->bindParam(1, $tagId);
            $isExists->bindParam(2, $news->id);
            $isExists->execute();
            $isExists = count($isExists->fetchAll(PDO::FETCH_CLASS)) > 0;

            if ($old->tytul === "Noworoczna trasa") {
                var_dump($isExists, $tagId, $news);
                var_dump("\n");
            }

            if (!$isExists) {
                $saveStmt = $db->prepare("INSERT INTO news_tag(id, news_id, tag_id) VALUES (UUID(), ?, ?)");
                $saveStmt->bindParam(1, $news->id);
                $saveStmt->bindParam(2, $tagId);
                $saveStmt->execute();
            }
        }
    }
}

//saveCategories($db, $tagsData);
//saveRawTagsData($db, $tagsData);
saveNewsTags($db, $tagsData);
