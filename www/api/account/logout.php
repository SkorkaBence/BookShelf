<?php
require_once(__DIR__ . "/../../../lib/autoload.php");
use BookShelf\Api\IO;
use BookShelf\User\Session;

Session::Init();
Session::LogOut();
IO::redirect("../../");