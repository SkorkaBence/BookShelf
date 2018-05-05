<?php
require_once(__DIR__ . "/../lib/autoload.php");
use BookShelf\User\Session;
use BookShelf\Api\IO;
$tmp = new BookShelf\Template\Core();
$sql = new BookShelf\Database\Sql();

Session::Init();
if (Session::IsLoggedIn()) {
    IO::redirect("home");
}

$data = [
    "usercount" => $sql->execute("SELECT id FROM users")->rowCount(),
    "bookcount" => $sql->execute("SELECT owner, id FROM books")->rowCount()
];

echo $tmp->get("guest/welcome.html", $data);