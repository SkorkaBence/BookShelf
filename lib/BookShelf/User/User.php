<?php

namespace BookShelf\User;

use BookShelf\Database\Sql;
use Exception;

class User {

    private $db;
    private $id;
    private $data;

    public function __construct(string $id) {
        $this->db = new Sql();
        $this->id = $id;
        $this->reload();
    }

    private function reload() {
        $users = $this->db->select("SELECT * FROM users WHERE id=:id", [
            ":id" => $this->id
        ]);

        if (count($users) != 1) {
            throw new Exception("Invalid user id");
        }

        $this->data = $users[0];
    }

    public function ChangePassword(string $new_password) {
        if (strlen($new_password) < 6) {
            throw new Exception("The password must be at least 6 characters long");
        }

        $this->db->execute("UPDATE users SET password_hash=:ph WHERE id=:id", [
            ":id" => $this->id,
            ":ph" => password_hash($new_password, PASSWORD_DEFAULT)
        ]);

        $this->reload();
    }

    public function CheckPassword(string $pw) : bool {
        return password_verify($pw, $this->data["password_hash"]);
    }

    public function getId() : string {
        return $this->data["id"];
    }

    public function GetUserData() : array {
        return [
            "id" => $this->data["id"],
            "name" => $this->data["name"],
            "email" => $this->data["email"],
            "image" => $this->data["image"] != null ? $this->data["image"] : "img/default_profile_picture.jpg"
        ];
    }

    public function GetBookList() : array {
        
    }

}