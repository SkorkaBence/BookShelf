<?php

namespace BookShelf\FileManager;

use BookShelf\Exceptions\DisplayableException;
use Ramsey\Uuid\Uuid;
use Exception;

abstract class GenericUploader {

    protected $dir;
    protected $mime_filters = [];
    protected $max_file_size = 50 * 1024 * 1024;

    public function __construct(string $dir) {
        $this->dir = $dir;
        if (!file_exists($this->dir)) {
            mkdir($this->dir, 0777, true);
        }
        if (!is_writable($this->dir)) {
            throw new Exception("This directory must be writeable: " . $this->dir);
        }
    }

    public abstract function UploadFile(array $file) : string;

    protected function isFileOk(array $file) {
        if (!isset($file["tmp_name"])) {
            throw new Exception("This is not a file.");
            return false; // unreachable code
        }

        $mime_type = mime_content_type($file["tmp_name"]);

        if (count($this->mime_filters) > 0) {
            $ok = false;
            foreach ($this->mime_filters as $filter) {
                if ($mime_type == $filter) {
                    $ok = true;
                    break;
                }
            }
            if (!$ok) {
                throw new DisplayableException("Hibás file típus. Megengedett típusok: " . implode(" ", $this->mime_filters));
                return false; // unreachable code
            }
        }

        if (filesize($file["tmp_name"]) > $this->max_file_size) {
            throw new DisplayableException("A feltöltött file túl nagy");
            return false; // unreachable code
        }

        return true;
    }

}