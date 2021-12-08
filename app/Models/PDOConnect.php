<?php

namespace app\Models;

use PDO;
use Exception;

use app\Abstracts\DBEntityAbstract;
use app\Interfaces\PDOConnectInterface;

class PDOConnect extends DBEntityAbstract implements PDOConnectInterface {

    protected PDO $db;

    public function setHost($value) {
        $this->host = $value;
    }

    public function setUser($value) {
        $this->user = $value;
    }

    public function setPassword($value) {
        $this->password = $value;
    }

    public function setDatabase($value) {
        $this->database = $value;
    }

    public function setCharset($value) {
        $this->charset = $value;
    }

    public function fillByRawData($aData) {

        if (isset($aData['host'])) {
            $this->setHost($aData['host']);
        }

        if (isset($aData['user'])) {
            $this->setUser($aData['user']);
        }

        if (isset($aData['password'])) {
            $this->setPassword($aData['password']);
        }

        if (isset($aData['database'])) {
            $this->setDatabase($aData['database']);
        }

        if (isset($aData['charset'])) {
            $this->setCharset($aData['charset']);
        }

    }

    public function connect() {

        $opt = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        $dsn = 'mysql:host='.$this->host.';dbname='.$this->database.';charset='.$this->charset.';';

        try {

            $this->db = new PDO($dsn, $this->user, $this->password, $opt);

        } catch (Exception $e) {

            die('MariaDB (PDO): Unable to connect. Error: '.$e."\n");

        }

        return $this->db;

    }
}