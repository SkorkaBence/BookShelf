<?php

namespace BookShelf\Books;

use BookShelf\Database\Sql;
use BookShelf\Exceptions\DisplayableException;
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

    public function changeAuthor($name) {
        $name = trim($name);
        if ($name == "") {
            throw new DisplayableException("A szerző neve nem lehet üres");
        }
        $this->data["author"] = $name;
    }

    public function changeTitle($name) {
        $name = trim($name);
        if ($name == "") {
            throw new DisplayableException("A cím nem lehet üres");
        }
        $this->data["title"] = $name;
    }

    public function changePages($p) {
        $this->data["pages"] = intval($p);
    }

    public function changeCategory($c) {
        $c = trim($c);
        $this->data["category"] = $c;
    }

    public function changeIsbn($isbn) {
        $isbn = trim($isbn);
        $this->data["isbn"] = $isbn;
    }

    public function changeReadStatus($s) {
        $this->data["hasread"] = ($s ? "true" : "false");
    }

    public function commitChanges() {
        $this->db->execute("UPDATE books SET author=:author, title=:title, pages=:pages, category=:category, isbn=:isbn, hasread=:hasread WHERE id=:id", [
            ":author" => $this->data["author"],
            ":title" => $this->data["title"],
            ":pages" => $this->data["pages"],
            ":category" => $this->data["category"],
            ":isbn" => $this->data["isbn"],
            ":hasread" => $this->data["hasread"],
            ":id" => $this->data["id"]
        ]);
    }

}