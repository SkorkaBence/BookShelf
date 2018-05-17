<?php

namespace BookShelf\FileManager;

use BookShelf\FileManager\GenericUploader;
use BookShelf\Exceptions\DisplayableException;
use Ramsey\Uuid\Uuid;
use Exception;

class ProfilePictureUploader extends GenericUploader {

    private $max_size = 500;

    public function __construct() {
        parent::__construct(ROOT_DIR . "/www/img/profile");

        $this->mime_filters = [
            "image/png",
            "image/jpg",
            "image/jpeg",
            "image/bmp"
        ];
    }

    public function UploadFile(array $file) : string {
        if (!$this->isFileOk($file)) {
            return null; // unreachable code, isFileOk throws DisplayableException exceptions
        }

        $img = imagecreatefromstring(file_get_contents($file["tmp_name"]));
        $width = imagesx($img);
        $height = imagesy($img);

        $ratio = $width / $height;

        if ($ratio > 1) {
            // width > height
            $new_width = $this->max_size;
            $new_height = $this->max_size / $ratio;
        } else {
            // height > width
            $new_width = $this->max_size * $ratio;
            $new_height = $this->max_size;
        }

        $resized_image = imagecreatetruecolor($new_width, $new_height);
        imagecopyresampled($resized_image, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);

        $uuid4 = Uuid::uuid4();
        $id = $uuid4->toString();

        imagepng($resized_image, $this->dir . "/" . $id . ".png");

        imagedestroy($img);
        imagedestroy($resized_image);

        return "img/profile/" . $id . ".png";
    }

}