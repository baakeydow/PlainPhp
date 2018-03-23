<?php
namespace Controllers;

use Model\AppComponent;

class IndexController extends AppComponent {

    public function getNews($start = -1, $limit = -1) {
        return $this->newsManager->getNewsList($start, $limit);
    }

    public function getOne($id) {
        return $this->newsManager->getNewsById($id);
    }
}
