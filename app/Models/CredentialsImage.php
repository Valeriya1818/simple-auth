<?php

namespace app\Models;

use app\Abstracts\ImageEntityAbstract;
use app\Interfaces\CredentialsImageInterface;

class CredentialsImage extends ImageEntityAbstract implements CredentialsImageInterface {

    protected array $credentials;

    public function getFileName(): string {
        return $this->filename;
    }

    public function setFileName($value) {
        $this->filename = $value;
    }

    public function setSources($value) {
        $this->sources = $value;
    }

    public function getCredentials(): array {
        return $this->credentials;
    }

    public function setCredentials($value) {
        $this->credentials = $value;
    }

    public function loadFromDisk() {

        if (realpath($this->getFileName())) {

            $credentials = [];
            $this->setSources(file($this->getFileName()));

            for ($i=0;count($this->sources)>$i;$i++) {

                $this->sources[$i] = trim($this->sources[$i]);

                if (mb_substr($this->sources[$i],0,11) == '- PMA_HOST=') {

                    $this->sources[$i] = explode('=',$this->sources[$i]);
                    $credentials['host'] = $this->sources[$i]['1'];

                } elseif (mb_substr($this->sources[$i],0,11) == '- PMA_USER=') {

                    $this->sources[$i] = explode('=',$this->sources[$i]);
                    $credentials['user'] = $this->sources[$i]['1'];

                } elseif (mb_substr($this->sources[$i],0,15) == '- PMA_PASSWORD=') {

                    $this->sources[$i] = explode('=',$this->sources[$i]);
                    $credentials['password'] = $this->sources[$i]['1'];
                } elseif (mb_substr($this->sources[$i],0,16) == 'MYSQL_DATABASE: ') {

                    $this->sources[$i] = explode(': ', $this->sources[$i]);
                    $credentials['database'] = $this->sources[$i]['1'];

                }
            }

            $credentials['charset'] = 'utf8';
            $this->setCredentials($credentials);
        }
    }
}