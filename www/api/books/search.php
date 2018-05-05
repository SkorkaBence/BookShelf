<?php
require_once(__DIR__ . "/../../../lib/autoload.php");
use BookShelf\Api\IO;
use BookShelf\Exceptions\DisplayableException;
use BookShelf\User\UserManager;
use BookShelf\User\Session;
use BookShelf\Books\BookRequester;

$query = IO::get("q");

Session::Init();
if (!Session::IsLoggedIn()) {
    IO::error("Not logged in");
}
$user = Session::getUser();

IO::print(BookRequester::BookSearch($query));