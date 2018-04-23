<?php
namespace Controllers;

use Model\AppComponent;

class IndexController extends AppComponent {

    public function index() {
        if (isset($_GET['id']) && !$this->getOne((int) $_GET['id'])) {
            $this->session->kick('News not found');
        }
        $this->page->addVar('Ctrl', $this);
        $this->page->send();
    }

    public function getNews($start = -1, $limit = -1) {
        return $this->newsManager->getList($start, $limit);
    }

    public function getOne($id) {
        return $this->newsManager->getById($id);
    }
}
