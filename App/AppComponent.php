<?php

use Lib\Page;
use Lib\Session;
use Lib\HTTPRequest;
use Model\Entity\News;
use Model\Entity\User;
use Controller\MainController;

class AppComponent {

    protected $ctrl,
              $session,
              $req;

    public function __construct(PDO $db)
    {
        $this->session = new Session;
        $this->ctrl = new MainController($db, $this->session);
        $this->req = new HTTPRequest;
    }

    public function getRoute($URI)
    {
        if ($URI === '/') {
            $this->indexView();
        } else if ($URI === '/admin') {
            $this->controlAccess();
        } else if ($URI === '/out') {
            $this->session->kick('loging out');
        } else {
            require '../Public/pages/404.html';
        }
    }

    private function controlAccess()
    {
        if ($this->session->isAuthenticated() || $this->ctrl->login($this->req)) {
            $this->adminView();
        } else {
            require '../Public/pages/login.php';
        }
    }

    public function indexView() {
        $page = new Page('home');
        if (isset($_GET['id']) && !$this->ctrl->getOne('news', (int) $_GET['id'])) {
            $this->session->kick('News not found');
        }
        $page->addVar('Ctrl', $this->ctrl);
        $page->send();
    }

    public function adminView() {
        $page = new Page('admin');
        $page->addVar('Ctrl', $this->ctrl);
        // news
        if (isset($_GET['modif'])) {
            $news = $this->ctrl->getOne('news', (int) $_GET['modif']);
            if (!$news) {
                $this->session->kick('news not found');
            }
            $page->addVar('news', $news);
        }
        if (isset($_GET['delete'])) {
            if (isset($_SESSION['level']) && $_SESSION['level'] == '1') {
                $this->ctrl->delete('news', (int) $_GET['delete']);
                $page->addVar('message', 'The news has been removed !');
            } else {
                $this->session->kick('user not allowed to delete news');
            }
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
                $news->set('id', $_POST['id']);
            }
            if ($news->isValid()) {
                $this->ctrl->save('news', $news, $news->isNew());
                $message = $news->isNew() ? 'The news has been added !' : 'The news has been modified !';
                $page->addVar('message', $message);
                unset($news);
            } else {
                $page->addVar('errors', $news->get('errors'));
            }
        }
        // user
        if (isset($_GET['editUser'])) {
            $user = $this->ctrl->getOne('users', (int) $_GET['editUser']);
            if (!$user) {
                $this->session->kick('User not found');
            }
            $page->addVar('user', $user);
        }
        if (isset($_GET['delUser'])) {
            $this->ctrl->delete('users', (int) $_GET['delUser']);
            $page->addVar('userNotice', 'The user has been removed !');
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
                $user->set('id', $_POST['userId']);
            }
            if ($user->isValid()) {
                $this->ctrl->save('users', $user, false);
                $userNotice = $user->isNew() ? 'User has just been added !' : 'User has just been modified !';
                $page->addVar('userNotice', $userNotice);
                unset($user);
            } else {
                $page->addVar('errors', $user->get('errors'));
            }
        }
        $page->send();
    }
}
