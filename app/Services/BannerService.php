<?php

namespace app\Services;

use Exception;

use app\Models\Banner as Banner;
use app\Models\Visitor as Visitor;
use app\Factories\VisitorFactory as VisitorFactory;
use app\Services\PDOQueryService as PDOQueryService;

class BannerService {

    protected string $user_ip;
    protected string $user_agent;
    protected string $view_date;
    protected string $page_url;
    protected string $views_count;

    protected PDOQueryService $queryBuilder;
    protected VisitorFactory $visitorsFactory;
    protected Banner $banner;

    public function __construct(PDOQueryService $queryBuilder, VisitorFactory $visitorFactory, Banner $banner) {

        $this->queryBuilder = $queryBuilder;
        $this->visitorsFactory = $visitorFactory;
        $this->banner = $banner;

        $this->user_ip = $_SERVER["REMOTE_ADDR"] ?? '127.0.0.1';
        $this->user_agent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        $this->view_date = date('Y-m-d H:i:s');
        $this->page_url = $_SERVER['HTTP_REFERER'] ?? '';
        $this->views_count = 1;

    }

    public function display() {

        try {

            $oItem = new Visitor($this->queryBuilder);
            $oItem->setId(0);
            $oItem->setIpAddress($this->user_ip);
            $oItem->setUserAgent($this->user_agent);

            $aItems = $this->visitorsFactory->retrieveItem(false,$this->user_ip, $this->user_agent, $this->page_url);
            foreach ($aItems as $obj) {

                $this->views_count = $obj->getViewsCount()+1;
                $oItem = $obj;
            }

            $oItem->setPageUrl($this->page_url);
            $oItem->setViewsCount($this->views_count);
            $oItem->setViewDate($this->view_date);
            $oItem->save();

            $this->banner->display();

        } catch (Exception $e) {

            die('The error is occurred: '.$e);
        }

    }

}