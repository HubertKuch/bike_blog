<?php

error_reporting(E_ERROR | E_PARSE);

$root = "/var/bike-blog/img/";
$db = new PDO("mysql:host=127.0.0.1;dbname=bike_blog;port=3306", "root", "");

$newsQuery = "SELECT * FROM bike_blog.news";
$insertImageQuery = "INSERT INTO bike_blog.images VALUE(UUID(), ?, ?)";
$newsStmt = $db->prepare($newsQuery);
$newsStmt->execute();

foreach ($newsStmt->fetchAll(PDO::FETCH_CLASS) as $news) {
    try {
        rename($root . $news->date, $root . $news->id);

        $dir = scandir($root . $news->id);

        if(!$dir) {
            continue;
        }

        $dir = array_filter($dir, fn($file) => str_ends_with(trim($file), "jpg"));

        foreach ($dir as $file) {
            $insertStmt = $db->prepare($insertImageQuery);
            $insertStmt->bindParam(1, $file, PDO::PARAM_STR);
            $insertStmt->bindParam(2, $news->id, PDO::PARAM_STR);

            $insertStmt->execute();
        }

    } catch (Exception) {
    }
}
