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

$method = IO::getMethod();
if ($method !== "PATCH") {
    IO::error("Unsupported request");
}

$data = IO::getFullData();

try {
    if (isset($data["name"])) {
        $user->changeName($data["name"]);
    }
    if (isset($data["email"])) {
        $user->changeEmail($data["email"]);
    }
    if (isset($data["current_password"], $data["new_password1"], $data["new_password2"])) {
        if (!$user->CheckPassword($data["current_password"])) {
            throw new DisplayableException("Hibás jelszó");
        }
        $user->changePassword($data["new_password1"], $data["new_password2"]);
    }
    $user->commitChanges();
} catch (DisplayableException $e) {
    IO::error($e->getMessage());
}

IO::redirect("../../account");