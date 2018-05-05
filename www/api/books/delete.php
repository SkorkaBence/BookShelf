<?php
require_once(__DIR__ . "/../../../lib/autoload.php");
use BookShelf\Api\IO;
use BookShelf\Exceptions\DisplayableException;
use BookShelf\User\UserManager;
use BookShelf\User\Session;
use BookShelf\Books\BookRequester;

$id = IO::get("id");

Session::Init();
if (!Session::IsLoggedIn()) {
    IO::error("Not logged in");
}
$user = Session::getUser();

$book = BookRequester::GetBook($user->getId(), $id);
$book->Delete();

IO::print();