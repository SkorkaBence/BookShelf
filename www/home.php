<?php
require_once(__DIR__ . "/../lib/autoload.php");
use BookShelf\User\Session;
use BookShelf\Api\IO;
$tmp = new BookShelf\Template\Core();
$sql = new BookShelf\Database\Sql();

Session::Init();
if (!Session::IsLoggedIn()) {
    IO::redirect("login");
    exit;
}
$user = Session::getUser();

$data = [
    "user" => $user->GetUserData(),
    "page" => isset($_GET["page"]) ? intval($_GET["page"]) : 1,
    "query" => isset($_GET["q"]) ? $_GET["q"] : ""
];

echo $tmp->get("user/list.html", $data);