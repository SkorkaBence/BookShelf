<?php
require_once(__DIR__ . "/../lib/autoload.php");
$tmp = new BookShelf\Template\Core();

$data = [
    "captcha_key" => $_CONFIG["recaptcha"]["site_key"]
];
if (isset($_GET["error"])) {
    $data["error"] = $_GET["error"];
}

echo $tmp->get("guest/register.html", $data);