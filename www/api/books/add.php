<?php
require_once(__DIR__ . "/../../../lib/autoload.php");
use BookShelf\Api\IO;
use BookShelf\Exceptions\DisplayableException;
use BookShelf\User\UserManager;
use BookShelf\User\Session;
use BookShelf\Books\BookRequester;

$author = IO::data("author");
$title = IO::data("title");
$pages = intval(IO::data("pages", 0));
$category = IO::data("category", "");
$isbn = IO::data("isbn", "");
$hasread = (IO::data("hasread", "false") != "false");

Session::Init();
if (!Session::IsLoggedIn()) {
    IO::error("Not logged in");
}
$user = Session::getUser();

BookRequester::AddBook([
    "owner" => $user->getId(),
    "author" => $author,
    "title" => $title,
    "pages" => $pages,
    "category" => $category,
    "isbn" => $isbn,
    "hasread" => $hasread
]);

IO::redirect("../../home");