<?php

namespace BookShelf\Template;

use Dwoo\Core as DwooCore;

class Core extends DwooCore {

    public function __construct() {
        parent::__construct();
        parent::setTemplateDir(ROOT_DIR . "/templates");
        parent::setCompileDir(ROOT_DIR . "/templates/compiled");
    }

}