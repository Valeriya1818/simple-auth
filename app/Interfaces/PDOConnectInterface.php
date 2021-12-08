<?php

namespace app\Interfaces;

interface PDOConnectInterface {

    public function setHost(string $value);

    public function setUser(string $value);

    public function setPassword(string $value);

    public function setDatabase(string $value);

    public function setCharset(string $value);
}