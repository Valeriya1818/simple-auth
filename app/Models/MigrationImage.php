<?php

namespace app\Models;

use app\Abstracts\ImageEntityAbstract;
use app\Interfaces\MigrationImageInterface;

class MigrationImage extends ImageEntityAbstract implements MigrationImageInterface {

    protected array $migration;

    public function getFileName(): string {
        return $this->filename;
    }

    public function setFileName($value) {
        $this->filename = $value;
    }

    public function setSources($value) {
        $this->sources = $value;
    }

    public function getMigration(): array {
        return $this->migration;
    }

    public function setMigration($value) {
        $this->migration = $value;
    }

    public function loadFromDisk() {

        if (realpath($this->getFileName())) {

            $queries = [];
            $this->setSources(file($this->getFileName()));
            $sql = '';

            for ($i = 0; count($this->sources) > $i; $i++) {

                if (mb_substr($this->sources[$i], 0, 2) !== '/*' and mb_substr($this->sources[$i], 0, 2) !== '--') {

                    $sql .= trim($this->sources[$i]);

                    if (strpos($this->sources[$i], ';')) {
                        $queries[] = $sql;
                        $sql = '';
                    }

                }
            }

            $this->setMigration($queries);
        }
    }

}