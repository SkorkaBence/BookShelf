<?php

namespace BookShelf\User;

use BookShelf\Database\Sql;
use BookShelf\Exceptions\DisplayableException;
use BookShelf\User\User;
use Ramsey\Uuid\Uuid;
use Exception;

class UserManager {

    private $db;

    public function __construct() {
        $this->db = new Sql();
    }

    public function Register(array $user_data) : User {
        if (!isset($user_data["name"], $user_data["email"], $user_data["password"], $user_data["password2"])) {
            throw new DisplayableException("Hiányzó adatok");
        }

        if (!filter_var($user_data["email"], FILTER_VALIDATE_EMAIL)) {
            throw new DisplayableException("Az e-mail cím helytelen");
        }

        if ($user_data["password"] !== $user_data["password2"]) {
            throw new DisplayableException("A két jelszó nem egyezik");
        }
        
        if (strlen($user_data["password"]) < 6) {
            throw new DisplayableException("A jelszónak legalább 6 karakternek kell lennie");
        }

        $accounts = $this->db->select("SELECT * FROM users WHERE email=:email", [
            ":email" => $user_data["email"]
        ]);

        if (count($accounts) > 0) {
            throw new DisplayableException("Ezt az e-mail címet már használja egy másik felhasználó");
        }

        $uuid4 = Uuid::uuid4();
        $id = $uuid4->toString();

        $this->db->execute("INSERT INTO users (id, name, email) VALUES (:id, :name, :email)", [
            ":id" => $id,
            ":name" => $user_data["name"],
            ":email" => $user_data["email"]
        ]);

        $user = new User($id);
        $user->ChangePassword($user_data["password"]);

        return $user;
    }

    public function Login(array $user_data) : User{
        if (!isset($user_data["email"], $user_data["password"])) {
            throw new DisplayableException("Hiányzó adatok");
        }

        $accounts = $this->db->select("SELECT id FROM users WHERE email=:email", [
            ":email" => $user_data["email"]
        ]);

        if (count($accounts) != 1) {
            throw new DisplayableException("Hibás e-mail cím");
        }

        $user = new User($accounts[0]["id"]);

        if (!$user->CheckPassword($user_data["password"])) {
            throw new DisplayableException("Hibás jelszó");
        }

        return $user;
    }

}