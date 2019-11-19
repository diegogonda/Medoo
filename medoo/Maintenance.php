<?php

namespace Medoo;

trait Maintenance {

    public function truncate(string $table) {
        $this->exec("TRUNCATE TABLE $this->prefix$table");
    }

}
