<?php

namespace BookShelf\FileManager;

class DirectoryCreator {

    public static function createDirectory(string $dir) {
        $ok = false;
        do {
            echo "Cehcking directory..." . PHP_EOL;
        
            if (!file_exists($dir)) {
                echo "Directory does not exists." . PHP_EOL;
                echo "Creating directory..." . PHP_EOL;
                if (mkdir($dir, 0777, true)) {
                    chmod($dir, 0777);
                    echo "Directory created." . PHP_EOL;
                    $ok = true;
                } else {
                    echo "WARNING: Directory creation failed!" . PHP_EOL;
                    echo "Can I try to create the directory using this command: 'mkdir -m 0777 " . escapeshellarg($dir) . "'? [Y/n] ";
                    $r = readline();
                    if ($r == "Y") {
                        shell_exec("mkdir -m 0777 " . escapeshellarg($dir) . "");
                        echo "Command exectured." . PHP_EOL;
                    } else {
                        echo "OK. Automatic directory creation stopped. Please manually create the following directory: " . $dir . PHP_EOL;
                        echo "The web server MUST be able to write in that directory." . PHP_EOL;
                        $ok = true;
                    }
                }
            } else {
                if (is_dir($dir)) {
                    if (is_writable($dir)) {
                        echo "The directory is writeable." . PHP_EOL;
                        $ok = true;
                    } else {
                        echo "The directory exists, but not writeable." . PHP_EOL;
                        echo "Can I try to change the permissions with the following command: 'chmod 0777 " . escapeshellarg($dir) . "'? [Y/n] ";
                        $r = readline();
                        if ($r == "Y") {
                            shell_exec("chmod 0777 " . escapeshellarg($dir) . "");
                            echo "Command exectured." . PHP_EOL;
                        } else {
                            echo "OK. Automatic directory creation stopped. Please manually change the permissions for the following directory: " . $dir . PHP_EOL;
                            echo "The web server MUST be able to write in that directory." . PHP_EOL;
                            $ok = true;
                        }
                    }
                } else {
                    echo "WARNING: The template compilation directory is a file. Can I remove it? [Y/n]" . PHP_EOL;
                    unlink($dir);
                }
            }
        
            if (!$ok) {
                echo "Restarting..." . PHP_EOL;
            }
        } while(!$ok);
        
        echo "Done." . PHP_EOL;
    }

}