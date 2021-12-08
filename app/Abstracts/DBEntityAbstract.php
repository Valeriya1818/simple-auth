<?php

namespace app\Abstracts;

abstract class DBEntityAbstract {

    protected string $host;
    protected string $user;
    protected string $password;
    protected string $database;
    protected string $charset;

    abstract protected function connect();
}