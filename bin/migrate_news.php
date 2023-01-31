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

    $saveStmt->execute([$news->MaxPredkosc, $news->Czas, $newsId, $news->StanLicznikaPoczatkowy, $news->StanLicznikaPoczatkowy + $news->Przejechalem, $news->Przejechalem]);

    $saveStmt->execute([$news->MaxPredkosc, $news->Czas, $newsId, $news->StanLicznika2Poczatkowy, $news->StanLicznika2Koncowy, $news->Przejechalem,]);
}

foreach ($oldNews as $oldNew) {
//    saveNews($oldNew, $db);
    saveMeters($oldNew, $db);
}

echo "\x1b[32m Successfully migrated news, meters and tags! \n";
