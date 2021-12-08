<?php

namespace app\Models;

use app\Services\PDOQueryService as PDOQueryService;

class Visitor {

    protected int $id;
    protected string $ip_address;
    protected string $user_agent;
    protected string $view_date;
    protected string $page_url;
    protected int $views_count;

    protected PDOQueryService $db;

    public function __construct(PDOQueryService $queryBuilder) {

        $this->db = $queryBuilder;
    }

    public function getId(): int {
        return $this->id ?? 0;
    }

    public function setId($value): void {
        $this->id = $value;
    }

    public function getIpAddress(): ?string {
        return $this->ip_address ?? null;
    }

    public function setIpAddress($value): void {
        $this->ip_address = $value;
    }

    public function getUserAgent(): ?string {
        return $this->user_agent ?? null;
    }

    public function setUserAgent($value): void {
        $this->user_agent = $value;
    }

    public function getViewDate(): ?string {
        return $this->view_date ?? null;
    }

    public function setViewDate($value): void {
        $this->view_date = $value;
    }

    public function getPageUrl(): ?string {
        return $this->page_url ?? null;
    }

    public function setPageUrl($value): void {
        $this->page_url = $value;
    }

    public function getViewsCount(): int {
        return $this->views_count ?? 1;
    }

    public function setViewsCount($value): void {
        $this->views_count = $value;
    }

    public function fillByRawData($sData=false) {

        $this->setId($sData['id'] ?? 0);
        $this->setIpAddress($sData['user_ip'] ?? '');
        $this->setUserAgent($sData['user_agent'] ?? '');
        $this->setViewDate($sData['view_date'] ?? '');
        $this->setPageUrl($sData['page_url'] ?? '');
        $this->setViewsCount($sData['views_count'] ?? 1);

    }

    public function save() {

        $set = [
            'user_ip' => $this->getIpAddress(),
            'user_agent' => $this->getUserAgent(),
            'view_date' => $this->getViewDate(),
            'page_url' => $this->getPageUrl(),
            'views_count' => $this->getViewsCount()
        ];

        if ($this->getId()) {

            $this->db->update();
            $this->db->where(['id'=>$this->getId()]);

        } else {

            $this->db->insert();
        }

        $this->db->table('visitors');
        $this->db->set($set);
        $this->db->exec();

    }

}