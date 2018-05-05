<?php
require_once(__DIR__ . "/../../../lib/autoload.php");
use BookShelf\Api\IO;
use BookShelf\Exceptions\DisplayableException;
use BookShelf\User\UserManager;
use BookShelf\User\Session;

$name = IO::data("name");
$email = IO::data("email");
$password = IO::data("password");
$password2 = IO::data("password2");

if (!IO::ValidateCaptcha()) {
    IO::redirect("../../register?" . http_build_query([
        "error" => "Robot vagy?"
    ]));
}

Session::Init();
$manager = new UserManager();

try {
    $user = $manager->Register([
        "name" => $name,
        "email" => $email,
        "password" => $password,
        "password2" => $password2
    ]);
    Session::LogIn($user);
    IO::redirect("../../");
} catch (DisplayableException $e) {
    IO::redirect("../../register?" . http_build_query([
        "error" => $e->getMessage()
    ]));
}