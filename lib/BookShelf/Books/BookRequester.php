<?php

namespace BookShelf\Books;

use BookShelf\Database\Sql;
use BookShelf\Books\Book;
use Ramsey\Uuid\Uuid;
use Exception;
use PDO;

class BookRequester {

    public static function GetPage(string $owner, int $page, string $query = "") : array {
        $sql = new Sql();

        $limit = 5;
        $skip = $limit * ($page - 1);

        $stmt = $sql->prepare("SELECT * FROM books WHERE owner=:owner and (author LIKE :query or title LIKE :query) ORDER BY title, author LIMIT :limit OFFSET :skip");
        $stmt->bindValue(":limit", $limit, PDO::PARAM_INT);
        $stmt->bindValue(":skip", $skip, PDO::PARAM_INT);
        $stmt->bindValue(":owner", $owner, PDO::PARAM_STR);
        $stmt->bindValue(":query", "%" . $query . "%", PDO::PARAM_STR);
        $stmt->execute();
        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $books_count_data = $sql->select("SELECT count(id) AS book_count FROM books WHERE owner=:owner and (author LIKE :query or title LIKE :query) ORDER BY title, author", [
            ":owner" => $owner,
            ":query" => "%" . $query . "%"
        ]);
        $book_count = intval($books_count_data[0]["book_count"]);

        $res = [];
        foreach ($books as $bookdata) {
            $res[] = new Book($bookdata);
        }

        return [
            "books" => $res,
            "has_next_page" => ($skip + $limit < $book_count),
            "has_previous_page" => ($book_count > 0 && $page > 1),
            "page_count" => ceil($book_count / $limit)
        ];
    }

    public static function GetBook(string $owner, string $id) {
        $sql = new Sql();
        $books = $sql->select("SELECT * FROM books WHERE id=:id and owner=:owner", [
            ":id" => $id,
            ":owner" => $owner
        ]);
        if (count($books) != 1) {
            throw new Exception("Invalid id");
        }
        return new Book($books[0]);
    }

    public static function BooksToJsonData(array $books) : array {
        $data = [];
        foreach ($books as $book) {
            $data[] = $book->GetBookData();
        }
        return $data;
    }

    public static function BookSearch(string $query) : array {
        $url = "https://www.googleapis.com/books/v1/volumes?" . http_build_query([
            "q" => $query
        ]);
        $data = json_decode(file_get_contents($url), true);

        $res = [];
        foreach ($data["items"] as $book) {
            $res[] = [
                "title" => isset($book["volumeInfo"]["title"]) ? $book["volumeInfo"]["title"] : "",
                "author" => isset($book["volumeInfo"]["authors"]) ? implode(", ", $book["volumeInfo"]["authors"]) : "",
                "pages" => isset($book["volumeInfo"]["pageCount"]) ? $book["volumeInfo"]["pageCount"] : 0,
                "category" => isset($book["volumeInfo"]["categories"]) ? implode(", ", $book["volumeInfo"]["categories"]) : "",
                "image" => isset($book["volumeInfo"]["imageLinks"]["thumbnail"]) ? $book["volumeInfo"]["imageLinks"]["thumbnail"] : ""
            ];
        }
        return $res;
    }

    public static function AddBook(array $data) {
        $sql = new Sql();
        
        $uuid4 = Uuid::uuid4();
        $id = $uuid4->toString();

        $sql->execute("INSERT INTO books (id, owner, author, title, pages, category, isbn, hasread) VALUES (:id, :owner, :author, :title, :pages, :category, :isbn, :hasread)", [
            "owner" => $data["owner"],
            ":id" => $id,
            ":author" => $data["author"],
            ":title" => $data["title"],
            ":pages" => $data["pages"],
            ":category" => $data["category"],
            ":isbn" => $data["isbn"],
            ":hasread" => $data["hasread"] ? "true" : "false"
        ]);
    }

}