<?php

namespace app\Interfaces;

interface CredentialsImageInterface {

    public function setFileName(string $value);

    public function setSources(array $value);

    public function setCredentials(array $value);

}