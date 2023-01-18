<?php

error_reporting(E_ERROR);


/** @var PDO $db */
$db = require "db.php";

$oldNewsStmt = $db->prepare("SELECT * FROM newsy");
$oldNewsStmt->execute();
$oldNews = $oldNewsStmt->fetchAll(PDO::FETCH_CLASS);

function saveNews($oldNews, PDO $db): void {
    $isExists = $db->prepare("SELECT id FROM news WHERE title = ? LIMIT 1");
    $isExists->execute([$oldNews->tytul]);

    if (($isExists->fetchAll(PDO::FETCH_CLASS)[0]->id)) {
        return;
    }

    $stmt = $db->prepare("INSERT INTO news(id, title, description, date) VALUE(UUID(), ?, ?, ?)");
    $stmt->execute([$oldNews->tytul, $oldNews->tresc, $oldNews->data]);
    $stmt->fetchAll();
    $stmt->closeCursor();
}

function getCategoryId(PDO $db) {
    $unnamedDescriptorStmt = $db->prepare("SELECT id FROM tags_categories LIMIT 1");
    $unnamedDescriptorStmt->execute();

    return $unnamedDescriptorStmt->fetchAll(PDO::FETCH_CLASS)[0]->id;
}

function saveRawTag(string $tag, string $descriptorId, PDO $db): void {
    $isExists = $db->prepare("SELECT id FROM tags WHERE tag = ? LIMIT 1");
    $isExists->execute([$tag]);

    if (($isExists->fetchAll(PDO::FETCH_CLASS)[0]->id)) {
        return;
    }

    $stmt = $db->prepare("INSERT INTO tags(id, tag, descriptor, category_id) VALUE (UUID(), ?, ? ,?)");
    $stmt->execute([$tag, 'test', $descriptorId]);
    $stmt->fetchAll();
    $stmt->closeCursor();
}

function saveNewsTags(stdClass $news, PDO $db): void {
    $tags = array_filter(array_unique(explode(";", $news->tagi)));

    $newsStmt = $db->prepare("SELECT id FROM news WHERE title = ?");
    $newsStmt->execute([$news->tytul]);

    $id = $newsStmt->fetchAll(PDO::FETCH_CLASS)[0]->id;

    foreach ($tags as $tag) {
        $tagIdStmt = $db->prepare("SELECT id FROM tags WHERE tag = ?");
        $tagIdStmt->execute([$tag]);
        $tagId = $tagIdStmt->fetchAll(PDO::FETCH_CLASS)[0]->id;

        $isExistsStmt = $db->prepare("SELECT id FROM news_tag WHERE news_id = ? AND tag_id = ?");
        $isExistsStmt->execute([$id, $tagId]);
        $isExists = $isExistsStmt->fetchAll(PDO::FETCH_CLASS)[0]->id;

        if ($isExists) {
            continue;
        }

        $saveStmt = $db->prepare("INSERT INTO news_tag(id, news_id, tag_id) VALUE (UUID(), ? ,?)");
        $saveStmt->execute([$id, $tagId]);
    }
}

function saveTags(stdClass $oldNews, PDO $db): void {
    $unnamedCategoryId = getCategoryId($db);

    if (!$unnamedCategoryId) {
        $db->exec("INSERT INTO tags_categories(id, category) VALUE (UUID(), 'unnamed_category')");
    }

    $unnamedCategoryId = getCategoryId($db);

    $tags = array_filter(array_unique(explode(";", $oldNews->tagi)));

    foreach ($tags as $tag) {
        saveRawTag($tag, $unnamedCategoryId, $db);
    }
}

function saveMeters(stdClass $news, PDO $db): void {
    $newsStmt = $db->prepare("SELECT id FROM news WHERE title = ?");
    $newsStmt->execute([$news->tytul]);

    $newsId = $newsStmt->fetchAll(PDO::FETCH_CLASS)[0]->id;

    $hasMetersStmt = $db->prepare("SELECT id FROM meter WHERE news_id = ?");
    $hasMetersStmt->execute([$newsId]);
    $hasMeters = !(count($hasMetersStmt->fetchAll(PDO::FETCH_CLASS)) == 0);

    if ($hasMeters) {
        return;
    }

    $saveStmt = $db->prepare("INSERT INTO meter(id, max_speed, time, news_id, meter_start_state, meter_end_state, trip_length) VALUE (UUID(), ?, ?, ?, ?, ?, ?)");

    $saveStmt->execute([$news->MaxPredkosc, $news->Czas, $newsId, $news->StanLicznikaPoczatkowy, $news->StanLicznika2Koncowy, $news->Przejechalem,]);

    $saveStmt->execute([$news->MaxPredkosc, $news->Czas, $newsId, $news->StanLicznika2Poczatkowy, $news->StanLicznika2Koncowy, $news->Przejechalem,]);
}

foreach ($oldNews as $oldNew) {
    saveNews($oldNew, $db);
    saveTags($oldNew, $db);
    saveNewsTags($oldNew, $db);
    saveNewsTags($oldNew, $db);
    saveMeters($oldNew, $db);
}

echo "\x1b[32m Successfully migrated news, meters and tags! \n";
