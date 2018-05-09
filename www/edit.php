<?php
require_once(__DIR__ . "/../lib/autoload.php");
use BookShelf\User\Session;
use BookShelf\Api\IO;
use BookShelf\Books\BookRequester;
$tmp = new BookShelf\Template\Core();
$sql = new BookShelf\Database\Sql();

Session::Init();
if (!Session::IsLoggedIn()) {
    IO::redirect("login");
    exit;
}
$user = Session::getUser();

if (!isset($_GET["id"])) {
    IO::redirect("home");
    exit;
}

$book;
try {
    $book = BookRequester::GetBook($user->getId(), $_GET["id"]);
} catch (Exception $e) {
    IO::redirect("home");
    exit;
}

$data = [
    "user" => $user->GetUserData(),
    "book" => $book->GetBookData()
];

echo $tmp->get("user/edit.html", $data);