<?php
namespace Strategy;

use Lib\HTTPRequest;
use Model\AppComponent;
use Model\News;
use Controllers\IndexController;
use Controllers\AdminController;
use Utils\Config;

class SetENV extends AppComponent {

    public function getRoute($URI) {
        if ($URI === '/') {
            $Ctrl = new IndexController($this->db);
            $this->setIndexView($Ctrl);
        } else if ($URI === '/admin') {
            $Ctrl = new AdminController($this->db);
            $this->controlAccess($Ctrl);
        } else if ($URI === '/out') {
            session_unset();
            header('Location: /');
        } else {
            require 'App/Web/404.html';
        }
    }

    private function login(HTTPRequest $request) {
        if (!$request->postExists('username')) {
            return false;
        }
        $login = $request->postData('username');
        $password = $request->postData('passwd');
        $config = new Config;
        if ($login == $config->get('login') && $password == $config->get('pass')) {
            $this->user->setAuthenticated(true);
            return true;
        }
        $this->user->setFlash('Username or Password Invalid ! try again...');
        return false;
    }

    public function controlAccess($Ctrl) {
        if ($this->user->isAuthenticated() || $this->login($this->HTTPRequest)) {
            self::setAdminView($Ctrl);
        } else {
            require 'App/Web/login.php';
        }
    }

    public function setAdminView($Ctrl) {
        if (isset($_GET['modif'])) {
            $news = $Ctrl->getOne((int) $_GET['modif']);
        }
        if (isset($_GET['delete'])) {
            $Ctrl->delete((int) $_GET['delete']);
            $message = 'The news has been removed !';
        }
        if (isset($_POST['author'])) {
            $news = new News(
                [
                    'author' => $_POST['author'],
                    'title' => $_POST['title'],
                    'content' => $_POST['content']
                ]
            );
            if (isset($_POST['id'])) {
                $news->setId($_POST['id']);
            }
            if ($news->isValid()) {
                $Ctrl->save($news);
                $message = $news->isNew() ? 'The news has been added !' : 'The news has been modified !';
            } else {
                $errors = $news->getErrors();
            }
        }
        require 'App/Web/admin.php';
    }

    public static function setIndexView($Ctrl) {
        require 'App/Web/index.php';
    }

}
