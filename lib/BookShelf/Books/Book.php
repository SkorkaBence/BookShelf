<?php

namespace BookShelf\Books;

use BookShelf\Database\Sql;
use Exception;

class Book {

    private $db;
    private $data;

    public function __construct(array $data) {
        $this->db = new Sql();
        $this->data = $data;
    }

    public function GetBookData() : array {
        return [
            "id" => $this->data["id"],
            "author" => $this->data["author"],
            "title" => $this->data["title"],
            "pages" => intval($this->data["pages"]),
            "category" => $this->data["category"],
            "isbn" => $this->data["isbn"],
            "hasread" => ($this->data["hasread"] == "true")
        ];
    }

    public function ToggleReadStatus() {
        $this->db->execute("UPDATE books SET hasread=:hasread WHERE id=:id", [
            ":hasread" => ($this->data["hasread"] == "true" ? "false" : "true"),
            ":id" => $this->data["id"]
        ]);
    }

    public function Delete() {
        $this->db->execute("DELETE FROM books WHERE id=:id", [
            ":id" => $this->data["id"]
        ]);
    }

}