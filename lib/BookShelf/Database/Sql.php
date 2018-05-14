<?php

namespace BookShelf\Database;

use PDO;
use PDOStatement;
use Exception;

class Sql {

    private static $connection = null;

    public function __construct() {
        global $_CONFIG;
        if (!isset($_CONFIG, $_CONFIG["mysql"], $_CONFIG["mysql"]["database"], $_CONFIG["mysql"]["host"], $_CONFIG["mysql"]["username"], $_CONFIG["mysql"]["password"])) {
            throw new Exception("Config must be loaded first");
        }

        if (self::$connection === null) {
            try {
                self::$connection = new PDO("mysql:dbname=" . $_CONFIG["mysql"]["database"] . ";host=" . $_CONFIG["mysql"]["host"] . ";charset=utf8", $_CONFIG["mysql"]["username"], $_CONFIG["mysql"]["password"], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);
            } catch (Exception $e) {
                throw new Exception("Connection failed. Exception: " . $e->getMessage());
            }
        }
    }

    public function select(string $query, array $params = []) : array {
        $stmt = self::$connection->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function execute(string $query, array $params = []) : PDOStatement {
        $stmt = self::$connection->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }

    public function prepare(string $query) : PDOStatement {
        return self::$connection->prepare($query);
    }

}