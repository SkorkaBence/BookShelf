<?php

namespace BookShelf\User;

use BookShelf\Database\Sql;
use BookShelf\Exceptions\DisplayableException;
use BookShelf\FileManager\ProfilePictureUploader;
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

    public function ChangePassword(string $new_password, $_new_password2 = false) {
        if ($_new_password2 !== false) {
            if ($new_password !== $_new_password2) {
                throw new DisplayableException("A két jelszó nem egyezik");
            }
        }
        if (strlen($new_password) < 6) {
            throw new DisplayableException("A jelszónak inimum 6 karakter hosszúnak kell lennie");
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

    public function changeName(string $name) {
        $name = trim($name);
        if ($name == "") {
            throw new DisplayableException("A név nem lehet üres");
        }
        $this->data["name"] = $name;
    }

    public function changeEmail(string $mail) {
        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            throw new DisplayableException("Az e-mail cím helytelen");
        }
        $this->data["email"] = $mail;
    }

    public function changeImage(array $img) {
        $uploader = new ProfilePictureUploader();
        $url = $uploader->UploadFile($img);
        $this->data["image"] = $url;
    }

    public function commitChanges() {
        $this->db->execute("UPDATE users SET name=:name, email=:email, image=:image WHERE id=:id", [
            ":name" => $this->data["name"],
            ":email" => $this->data["email"],
            ":image" => $this->data["image"],
            ":id" => $this->id
        ]);
    }

}