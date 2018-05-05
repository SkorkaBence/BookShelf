<?php
require_once(__DIR__ . "/../../../lib/autoload.php");
use BookShelf\Api\IO;
use BookShelf\Exceptions\DisplayableException;
use BookShelf\User\UserManager;
use BookShelf\User\Session;
use BookShelf\Books\BookRequester;

$page = intval(IO::get("page"));
$query = IO::get("q", "");

Session::Init();
if (!Session::IsLoggedIn()) {
    IO::error("Not logged in");
}
$user = Session::getUser();

$books = BookRequester::GetPage($user->getId(), $page, $query);

$data = [
    "items" => BookRequester::BooksToJsonData($books["books"]),
    "has_next_page" => $books["has_next_page"],
    "has_previous_page" => $books["has_previous_page"],
    "page_count" => $books["page_count"]
];
IO::print($data);