<?php
namespace Strategy;

use Lib\HTTPRequest;
use Controllers\IndexController;
use Controllers\AdminController;
use Model\AppComponent;
use Model\News\News;
use Model\Users\User;

class ENV extends AppComponent {

    public function getRoute($URI) {
        if ($URI === '/') {
            $Ctrl = new IndexController($this->db);
            $this->setIndexView($Ctrl);
        } else if ($URI === '/admin') {
            $Ctrl = new AdminController($this->db);
            $this->controlAccess($Ctrl);
        } else if ($URI === '/out') {
            $this->session->kick('loging out');
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
        $Ctrl = new AdminController($this->db);
        $user = $Ctrl->getCredentials($login, $password);
        if ($user) {
            $this->session->setAuthenticated(true, $user);
            $Ctrl->saveAddedUser($user);
            return true;
        }
        $this->session->setFlash('Username or Password Invalid ! try again...');
        return false;
    }

    private function controlAccess($Ctrl) {
        if ($this->session->isAuthenticated() || $this->login($this->req)) {
            self::setAdminView($Ctrl);
        } else {
            require 'App/Web/login.php';
        }
    }

    private function setAdminView($Ctrl) {
        // news
        if (isset($_GET['modif'])) {
            $news = $Ctrl->getOne((int) $_GET['modif']);
            if (!$news) {
                $this->session->kick('news not found');
            }
        }
        if (isset($_GET['delete'])) {
            $Ctrl->delete((int) $_GET['delete']);
            $message = 'The news has been removed !';
        }
        if (isset($_POST['content'])) {
            $news = new News(
                [
                    'author' => $_SESSION['user'],
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
                unset($news);
            } else {
                $errors = $news->getErrors();
            }
        }
        // user
        if (isset($_GET['editUser'])) {
            $user = $Ctrl->getSingleUser((int) $_GET['editUser']);
            if (!$user) {
                $this->session->kick('User not found');
            }
        }
        if (isset($_GET['delUser'])) {
            $Ctrl->delSingleUser((int) $_GET['delUser']);
            $userNotice = 'The user has been removed !';
        }
        if (isset($_POST['admin'])) {
            $user = new User(
                [
                    'nickname' => $_POST['nickname'],
                    'email' => $_POST['email'],
                    'password' => $_POST['pwd'],
                    'accessLevel' => $_POST['admin']
                ]
            );
            if (isset($_POST['userId'])) {
                $user->setId($_POST['userId']);
            }
            if ($user->isValid()) {
                $Ctrl->saveAddedUser($user);
                $userNotice = $user->isNew() ? 'User has just been added !' : 'User has just been modified !';
                unset($user);
            } else {
                $userErrors = $user->getErrors();
            }
        }
        require 'App/Web/admin.php';
    }

    private function setIndexView($Ctrl) {
        if (isset($_GET['id']) && !$Ctrl->getOne((int) $_GET['id'])) {
            $this->session->kick('News not found');
        }
        require 'App/Web/index.php';
    }

}
