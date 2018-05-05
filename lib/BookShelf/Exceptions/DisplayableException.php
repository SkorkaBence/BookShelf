<?php

namespace BookShelf\Exceptions;

use Exception;

class DisplayableException extends Exception {

    public function __construct(string $msg) {
        parent::__construct($msg);
    }

}