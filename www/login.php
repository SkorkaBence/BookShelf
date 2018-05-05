<?php
require_once(__DIR__ . "/../lib/autoload.php");
$tmp = new BookShelf\Template\Core();

$data = [];
if (isset($_GET["error"])) {
    $data["error"] = $_GET["error"];
}

echo $tmp->get("guest/login.html", $data);