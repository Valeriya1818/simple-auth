<?php

namespace app\Factories;

use app\Models\Visitor;
use app\Services\PDOQueryService as PDOQueryService;

class VisitorFactory {

    protected PDOQueryService $db;

    public function __construct(PDOQueryService $queryBuilder) {
        $this->db = $queryBuilder;
    }

    public function retrieveItem($id=false,$user_ip=false,$user_agent=false,$page_url=false): array {

        $aData = [];

        if ($id) {
            $aData['id'] = $id;
        }

        if ($user_agent) {
            $aData['user_agent'] = $user_agent;
        }

        if ($user_ip) {
            $aData['user_ip'] = $user_ip;
        }

        if ($page_url) {
            $aData['page_url'] = $page_url;
        }

        $this->db->select();
        $this->db->table('visitors');
        $this->db->where($aData);
        $this->db->orderBy(['id' => 'desc']);
        $this->db->limit(1);

        $aData = [];
        foreach ($this->db->exec() as $value) {

            $oItem = new Visitor($this->db);
            $oItem->fillByRawData($value);
            $aData[] = $oItem;

        }

        return $aData;
    }
}