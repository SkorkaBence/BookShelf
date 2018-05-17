<?php

namespace BookShelf\User;

use BookShelf\User\User;
use Exception;

class Session {

    public static function Init() {
        session_start();
    }

    public static function get(string $key) {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        } else {
            return null;
        }
    }

    public static function set(string $key, $value) {
        $_SESSION[$key] = $value;
    }

    public static function LogIn(User $user) {
        session_regenerate_id();

        self::set("logged_in", true);
        self::set("user_id", $user->getId());
    }

    public static function LogOut() {
        self::set("logged_in", false);
        self::set("user_id", null);
    }

    public static function IsLoggedIn() {
        return (self::get("logged_in") === true);
    }

    public static function getUser() {
        if (!self::IsLoggedIn()) {
            throw new Exception("Not logged in");
        }
        try {
            $user = new User(self::get("user_id"));
        } catch (Exception $e) {
            $this->LogOut();
        }
        return $user;
    }

}