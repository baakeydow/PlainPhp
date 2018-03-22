<?php
namespace Controllers;

use PDO;
use Model\News\NewsManager;

class IndexController {

    protected $newsManager;

    public function __construct(PDO $db) {
        $this->newsManager = new NewsManager($db);
    }

    public function getNews($start = -1, $limit = -1) {
        return $this->newsManager->getNewsList($start, $limit);
    }

    public function getOne($id) {
        return $this->newsManager->getNewsById($id);
    }
}
