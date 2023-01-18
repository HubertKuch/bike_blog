<?php

require "migrate_news.php";
require "migrate_links.php";

echo "\x1b[33m You must manually migrate images by `php migrate_images.php --root=/path/to/actual/images/` and add `--full-migration` if you want migrate folder names.";
