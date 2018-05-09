<?php

namespace BookShelf\Api;

use Exception;

class IO {

    private static $can_print = true;
    private static $response_code = 200;
    private static $processed_data = null;

    public static function get(string $key, $default = null) {
        if (!isset($_GET[$key])) {
            if ($default === null) {
                self::error("Missing parameter: " . $key);
            } else {
                return $default;
            }
        }
        return $_GET[$key];
    }

    public static function data(string $key, $default = null) {
        if (isset($_POST[$key])) {
            return $_POST[$key];
        } else {
            $data = self::getFullData();
            if (isset($data[$key])) {
                return $data[$key];
            } else if ($default !== null) {
                return $default;
            } else {
                self::error("Missing data: " . $key);
            }
        }
    }

    public static function getFullData() {
        if (self::$processed_data !== null) {
            return self::$processed_data;
        }

        if (!isset($_SERVER["CONTENT_TYPE"])) {
            return [];
        }
        $content_type = $_SERVER["CONTENT_TYPE"];

        if ($content_type == "application/x-www-form-urlencoded") {
            $data = $_POST;
        } else if ($content_type == "application/json") {
            $data = json_decode(file_get_contents('php://input'), true);
        } else {
            self::error("Unrecognised content type: " . $content_type);
        }

        self::$processed_data = $data;

        return $data;
    }

    public static function print($data = null) {
        if (!self::$can_print) {
            throw new Exception("Only one IP print is allowed per session");
        }

        $output = "";

        if ($data === null) {
            self::$response_code = 204;
        } else {
            $output = json_encode($data, JSON_PRETTY_PRINT);
        }

        http_response_code(self::$response_code);
        header("Content-type: application/json; charset=utf-8");
        echo $output;

        self::$can_print = false;
    }

    public static function error($message) {
        self::$response_code = 400;
        self::print([
            "error" => $message
        ]);
        exit;
    }

    public static function getMethod() {
        $data = self::getFullData();
        if (isset($data["_method"])) {
            return strtoupper($data["_method"]);
        }
        return $_SERVER['REQUEST_METHOD'];
    }

    public static function redirect(string $url) {
        if (!self::$can_print) {
            throw new Exception("Redirect is only possible BEFORE printing");
        }

        header("Location: " . $url);
        header("Content-type: text/plain; charset=utf-8");
        echo $url . PHP_EOL;

        self::$can_print = false;
    }

    public static function ValidateCaptcha() {
        global $_CONFIG;
        $captcha = self::data("g-recaptcha-response");

        $url = "https://www.google.com/recaptcha/api/siteverify?" . http_build_query([
            "secret" => $_CONFIG["recaptcha"]["secret"],
            "response" => $captcha
        ]);
        $response = json_decode(file_get_contents($url), true);

        return $response["success"] === true;
    }

}