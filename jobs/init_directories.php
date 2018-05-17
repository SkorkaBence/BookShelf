<?php
require_once(__DIR__ . "/../lib/autoload.php");

BookShelf\FileManager\DirectoryCreator::createDirectory(ROOT_DIR . "/templates/compiled");
BookShelf\FileManager\DirectoryCreator::createDirectory(ROOT_DIR . "/www/img/profile");

?>