<?php
echo "KONYVESPOLC INSTALLER WIZARD" . PHP_EOL;
echo file_get_contents(__DIR__ . "/wizard.txt") . PHP_EOL;

// Config -----------------------------------------------------------------

if (!file_exists(__DIR__ . "/../config.php")) {
    echo "The config file is missing." . PHP_EOL;
    echo "MySQL server:" . PHP_EOL;
    echo "Host: ";
    $host = readline();
    echo "Database: ";
    $db = readline();
    echo "Username: ";
    $user = readline();
    echo "Password: ";
    $pw = readline();
    echo "reCAPTCHA:" . PHP_EOL;
    echo "Site key: ";
    $sik = readline();
    echo "Secret key: ";
    $sek = readline();

    file_put_contents(__DIR__ . "/../config.json", json_encode([
        "mysql" => [
            "host" => $host,
            "username" => $user,
            "password" => $pw,
            "database" => $db
        ],
        "recaptcha" => [
            "site_key" => $sik,
            "secret" => $sek
        ]
    ]));
    file_put_contents(__DIR__ . "/../config.php", '<?php $_CONFIG=json_decode(file_get_contents(__DIR__ . "/config.json"), true);');
} else {
    echo "Config file exists." . PHP_EOL;
}

// Composer   -----------------------------------------------------------------

echo "Updating composer..." . PHP_EOL;
shell_exec("cd " . escapeshellarg(__DIR__ . "/../lib/") . " && composer update");

// SQL  -----------------------------------------------------------------

echo "Running SQL script..." . PHP_EOL;
shell_exec("cd " . escapeshellarg(__DIR__ . "/") . " && php init_db.php");

// Templates   -----------------------------------------------------------------

echo "Running template script..." . PHP_EOL;
shell_exec("cd " . escapeshellarg(__DIR__ . "/") . " && php init_templates.php");

// TypeScript   -----------------------------------------------------------------

echo "Compiling typescirpt..." . PHP_EOL;
shell_exec("cd " . escapeshellarg(__DIR__ . "/../") . " && tsc -p tsconfig.json");

echo "Done." . PHP_EOL;