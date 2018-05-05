<?php
require_once(__DIR__ . "/../lib/autoload.php");
use BookShelf\User\Session;
use BookShelf\Api\IO;
$tmp = new BookShelf\Template\Core();

Session::Init();
if (Session::IsLoggedIn()) {
    IO::redirect("home");
}

$data = [];
if (isset($_GET["error"])) {
    $data["error"] = $_GET["error"];
}

echo $tmp->get("guest/login.html", $data);