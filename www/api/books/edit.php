<?php
require_once(__DIR__ . "/../../../lib/autoload.php");
use BookShelf\Api\IO;
use BookShelf\Exceptions\DisplayableException;
use BookShelf\User\UserManager;
use BookShelf\User\Session;
use BookShelf\Books\BookRequester;

Session::Init();
if (!Session::IsLoggedIn()) {
    IO::error("Not logged in");
}
$user = Session::getUser();

$id = IO::get("id");
try {
    $book = BookRequester::GetBook($user->getId(), $id);
} catch (Exception $e) {
    IO::error($e->getId());
}

$method = IO::getMethod();
if ($method !== "PATCH") {
    IO::error("Unsupported request");
}

$data = IO::getFullData();

try {
    if (isset($data["author"])) {
        $book->changeAuthor($data["author"]);
    }
    if (isset($data["title"])) {
        $book->changeTitle($data["title"]);
    }
    if (isset($data["pages"])) {
        $book->changePages($data["pages"]);
    }
    if (isset($data["category"])) {
        $book->changeCategory($data["category"]);
    }
    if (isset($data["isbn"])) {
        $book->changeIsbn($data["isbn"]);
    }
    if (isset($data["hasread"])) {
        $book->changeReadStatus(strtolower($data["hasread"]) === "true" || $data["hasread"] === true);
    }
    $book->commitChanges();
} catch (DisplayableException $e) {
    IO::error($e->getMessage());
}

IO::redirect("../../edit?id=" . $id);