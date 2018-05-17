<?php
require_once(__DIR__ . "/../lib/autoload.php");

echo "Testing connection..." . PHP_EOL;

$sql = null;
try {
    $sql = new BookShelf\Database\Sql();
} catch (Exception $e) {
    echo "Stopped. Exception: " . $e->getMessage() . PHP_EOL;
    exit;
}

echo "Initializing database..." . PHP_EOL;

try {
    $asd = $sql->select("SELECT * FROM users");
    echo "Table users already exists" . PHP_EOL;
} catch (Exception $e) {
    echo "Creating table: users" . PHP_EOL;
    $sql->execute(
        "CREATE TABLE `users` (
            `id` varchar(128) NOT NULL,
            `name` varchar(255) DEFAULT NULL,
            `email` varchar(254) DEFAULT NULL,
            `password_hash` text,
            `image` text,
            `regtime` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
          ) DEFAULT CHARSET=utf8;"
    );
    echo "Setting primary key and indexes for table: users" . PHP_EOL;
    $sql->execute(
        "ALTER TABLE `users`
        ADD PRIMARY KEY (`id`),
        ADD KEY `email` (`email`);"
    );
}

try {
    $asd = $sql->select("SELECT * FROM books");
    echo "Table books already exists" . PHP_EOL;
} catch (Exception $e) {
    echo "Creating table: books" . PHP_EOL;
    $sql->execute(
        "CREATE TABLE `books` (
            `id` varchar(128) NOT NULL,
            `owner` varchar(128) NOT NULL,
            `author` varchar(1024) DEFAULT NULL,
            `title` varchar(1024) DEFAULT NULL,
            `pages` int(11) NOT NULL DEFAULT '0',
            `category` varchar(128) DEFAULT NULL,
            `isbn` varchar(20) DEFAULT NULL,
            `hasread` enum('false','true') NOT NULL DEFAULT 'false'
          ) DEFAULT CHARSET=utf8;"
    );
    echo "Setting primary key and indexes for table: books" . PHP_EOL;
    $sql->execute(
        "ALTER TABLE `books`
        ADD PRIMARY KEY (`id`),
        ADD KEY `owner` (`owner`),
        ADD KEY `author` (`author`),
        ADD KEY `title` (`title`);"
    );
}

echo "Done." . PHP_EOL;