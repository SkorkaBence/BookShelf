<?php
require_once(__DIR__ . "/../../../lib/autoload.php");
use BookShelf\Api\IO;
use BookShelf\Exceptions\DisplayableException;
use BookShelf\User\UserManager;
use BookShelf\User\Session;

$email = IO::data("email");
$password = IO::data("password");

Session::Init();
$manager = new UserManager();

try {
    $user = $manager->Login([
        "email" => $email,
        "password" => $password
    ]);
    Session::LogIn($user);
    IO::redirect("../../");
} catch (DisplayableException $e) {
    IO::redirect("../../login?" . http_build_query([
        "error" => $e->getMessage()
    ]));
}