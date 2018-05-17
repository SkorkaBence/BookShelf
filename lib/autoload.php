<?php

define("ROOT_DIR", __DIR__ . "/..");

if (file_exists(ROOT_DIR . "/config.php")) {
    require_once(ROOT_DIR . "/config.php");
} else {
    header("Content-type: text/plain; charset=utf-8");
    echo "Az oldal nincs még feltelepítve." . PHP_EOL;
    echo "A telepítéshet köetni kell a mellékelt INSTALL_GUIDE.md útmutatót." . PHP_EOL;
    echo "Ez az útmutató elérhető az alábbi címen is: https://github.com/SkorkaBence/BookShelf/blob/master/INSTALL_GUIDE.md" . PHP_EOL;
    exit;
}

require_once(__DIR__ . "/vendor/autoload.php");
require_once(__DIR__ . "/BookShelf/AutoLoader.php");
BookShelf\AutoLoader::register();