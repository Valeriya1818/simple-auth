<?php

namespace app\Services;

use PDO;

class PDOQueryService {

    protected PDO $db;
    protected string $query;
    protected $vars;
    protected string $table;
    protected array $set;
    protected array $where;
    protected $orderBy;
    protected $limit;

    public function __construct($PDOConnect) {
        $this->db = $PDOConnect;
    }

    private function reset() {
        foreach($this as $key => $value) {
            if ($key!=='db') {
                unset($this->$key);
            }
        }
    }

    private function pdoVars() {

        if (isset($this->vars)) {

            $arr = [];

            if (is_array($this->vars)) {
                foreach($this->vars as $key => $value) {
                    $arr[] = is_numeric($key) ? $value : $key.' as '.$value;
                }
            } else {
                $arr[] = $this->vars;
            }

            $this->query .= implode(', ',$arr);
        }

    }

    private function pdoTable() {

        if (isset($this->table)) {

            $arr = [];

            if (is_array($this->table)) {
                foreach($this->table as $key => $value) {
                    $arr[] = is_numeric($key) ? $value : $key.' '.$value;
                }
            } else {
                $arr[] = $this->table;
            }

            $this->query .= $arr['0'];
        }

    }

    private function pdoSet(): array {

        $add = [];

        if (isset($this->set)) {

            if (is_array($this->set)) {

                $arr = [];

                $this->query .= ' SET ';
                foreach($this->set as $key => $value) {
                    $add[] = $value;
                    $arr[] = $key.'=?';
                }

                $this->query .= implode(', ',$arr);
            }
        }

        return $add;

    }

    private function pdoWhere(): array {

        $add = [];

        if (isset($this->where)) {

            if (is_array($this->where)) {

                $arr = [];

                $this->query .= ' WHERE ';
                foreach($this->where as $key => $value) {
                    $add[] = $value;
                    $arr[] = $key.'=?';
                }

                $this->query .= implode(' AND ',$arr);

            }
        }

        return $add;
    }

    private function pdoOrderBy() {

        if (isset($this->orderBy)) {

            $arr = [];
            $this->query .= ' ORDER BY ';

            if (is_array($this->orderBy)) {

                foreach($this->orderBy as $key => $value) {
                    $arr[] = $key.' '.$value;
                }

                $this->query .= implode(', ',$arr);

            } else {

                $this->query .= $this->orderBy;
            }
        }
    }

    private function pdoLimit() {

        $this->query .= $this->limit ? ' LIMIT '.$this->limit : '';
    }

    public function select($value='*') {
        $this->reset();
        $this->query = 'SELECT ';
        $this->vars = $value;
    }

    public function insert() {
        $this->reset();
        $this->query = 'INSERT ';
    }

    public function update() {
        $this->reset();
        $this->query = 'UPDATE ';
    }

    public function delete() {
        $this->reset();
        $this->query = 'DELETE ';
    }

    public function table($value) {
        $this->table = $value;
    }

    public function set($value) {
        $this->set = $value;
    }

    public function where($value) {
        $this->where = $value;
    }

    public function orderBy($value) {
        $this->orderBy = $value;
    }

    public function limit($value) {
        $this->limit = $value;
    }

    public function exec() {

        $bind = [];

        switch($this->query) {
            case 'SELECT ':
                $this->pdoVars();
                $this->query .= ' FROM ';
                $this->pdoTable();
                $bind = $this->pdoWhere();
                $this->pdoOrderBy();
                $this->pdoLimit();
                break;

            case 'INSERT ';
                $this->query .= 'INTO ';
                $this->pdoTable();
                $bind = [...$this->pdoSet(),...$this->pdoWhere()];
                break;

            case 'UPDATE ':
                $this->pdoTable();
                $bind = [...$this->pdoSet(),...$this->pdoWhere()];
                $this->pdoOrderBy();
                $this->pdoLimit();
                break;

            case 'DELETE ':
                $this->query .= ' FROM ';
                $this->pdoTable();
                $bind = [...$this->pdoWhere()];
                $this->pdoOrderBy();
                $this->pdoLimit();
                break;
        }

        $result = $this->db->prepare($this->query);
        if (!empty($bind)) {
            $result->execute($bind);
        }

        return $result->fetchAll();
    }

    public function prepareDatabase($aData) {

        $visitors = $this->db->query("SHOW TABLES like 'visitors'")->fetchAll(PDO::FETCH_COLUMN);
        if (empty($visitors)) {

            ini_set('display_errors', false);

            for ($i = 0; count($aData) > $i; $i++) {
                $this->db->query($aData[$i]);
            }

            ini_set('display_errors', true);

        }

    }

}