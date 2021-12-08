<?php

namespace app\Interfaces;

interface MigrationImageInterface {

    public function setFileName(string $value);

    public function setSources(array $value);

    public function setMigration(array $value);
}