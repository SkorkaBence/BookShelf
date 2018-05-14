<?php
require_once(__DIR__ . "/../lib/autoload.php");
define("TEMPLATE_COMPILED_DIR", ROOT_DIR . "/templates/compiled");

$ok = false;
do {
    echo "Cehcking directory..." . PHP_EOL;

    if (!file_exists(TEMPLATE_COMPILED_DIR)) {
        echo "Directory does not exists." . PHP_EOL;
        echo "Creating directory..." . PHP_EOL;
        if (mkdir(TEMPLATE_COMPILED_DIR, 0777, true)) {
            echo "Directory created." . PHP_EOL;
            $ok = true;
        } else {
            echo "WARNING: Directory creation failed!" . PHP_EOL;
            echo "Can I try to create the directory using this command: 'mkdir -m 0777 " . escapeshellarg(TEMPLATE_COMPILED_DIR) . "'? [Y/n] ";
            $r = readline();
            if ($r == "Y") {
                shell_exec("mkdir -m 0777 " . escapeshellarg(TEMPLATE_COMPILED_DIR) . "");
                echo "Command exectured." . PHP_EOL;
            } else {
                echo "OK. Automatic directory creation stopped. Please manually create the following directory: " . TEMPLATE_COMPILED_DIR . PHP_EOL;
                echo "The web server MUST be able to write in that directory." . PHP_EOL;
                $ok = true;
            }
        }
    } else {
        if (is_dir(TEMPLATE_COMPILED_DIR)) {
            if (is_writable(TEMPLATE_COMPILED_DIR)) {
                echo "The directory is writeable." . PHP_EOL;
                $ok = true;
            } else {
                echo "The directory exists, but not writeable." . PHP_EOL;
                echo "Can I try to change the permissions with the following command: 'chmod 0777 " . escapeshellarg(TEMPLATE_COMPILED_DIR) . "'? [Y/n] ";
                $r = readline();
                if ($r == "Y") {
                    shell_exec("chmod 0777 " . escapeshellarg(TEMPLATE_COMPILED_DIR) . "");
                    echo "Command exectured." . PHP_EOL;
                } else {
                    echo "OK. Automatic directory creation stopped. Please manually change the permissions for the following directory: " . TEMPLATE_COMPILED_DIR . PHP_EOL;
                    echo "The web server MUST be able to write in that directory." . PHP_EOL;
                    $ok = true;
                }
            }
        } else {
            echo "WARNING: The template compilation directory is a file. Can I remove it? [Y/n]" . PHP_EOL;
            unlink(TEMPLATE_COMPILED_DIR);
        }
    }

    if (!$ok) {
        echo "Restarting..." . PHP_EOL;
    }
} while(!$ok);

echo "Done." . PHP_EOL;
?>