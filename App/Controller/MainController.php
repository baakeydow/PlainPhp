<?php
namespace Controller;

use PDO;
use Lib\Page;
use Model\News\News;
use Model\Users\User;
use Model\Manager\AppManager;

class MainController {

    protected $appManager,
              $page;

    public function __construct(PDO $db, $session)
    {
        $this->session = $session;
        $this->appManager = new AppManager($db);
        $this->page = new Page('index');
    }

    public function indexView() {
        if (isset($_GET['id']) && !$this->getOne('news', (int) $_GET['id'])) {
            $this->session->kick('News not found');
        }
        $this->page->addVar('Ctrl', $this);
        $this->page->send();
    }

    public function adminView() {
        $this->page = new Page('admin');
        $this->page->addVar('Ctrl', $this);
        // news
        if (isset($_GET['modif'])) {
            $news = $this->getOne('news', (int) $_GET['modif']);
            if (!$news) {
                $this->session->kick('news not found');
            }
            $this->page->addVar('news', $news);
        }
        if (isset($_GET['delete'])) {
            if (isset($_SESSION['level']) && $_SESSION['level'] == '1') {
                $this->delete('news', (int) $_GET['delete']);
                $this->page->addVar('message', 'The news has been removed !');
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
                $news->setId($_POST['id']);
            }
            if ($news->isValid()) {
                $this->save('news', $news, $news->isNew());
                $message = $news->isNew() ? 'The news has been added !' : 'The news has been modified !';
                $this->page->addVar('message', $message);
                unset($news);
            } else {
                $this->page->addVar('errors', $news->getErrors());
            }
        }
        // user
        if (isset($_GET['editUser'])) {
            $user = $this->getOne('users', (int) $_GET['editUser']);
            if (!$user) {
                $this->session->kick('User not found');
            }
            $this->page->addVar('user', $user);
        }
        if (isset($_GET['delUser'])) {
            $this->delete('users', (int) $_GET['delUser']);
            $this->page->addVar('userNotice', 'The user has been removed !');
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
                $this->save('users', $user, false);
                $userNotice = $user->isNew() ? 'User has just been added !' : 'User has just been modified !';
                $this->page->addVar('userNotice', $userNotice);
                unset($user);
            } else {
                $this->page->addVar('errors', $user->getErrors());
            }
        }
        $this->page->send();
    }

    public function getThem($table , $start = -1, $limit = -1) {
        return $this->appManager->getList($table, $start, $limit);
    }

    public function getOne($table, $id) {
        if ($table == 'news') {
            return $this->appManager->getById($table, $id);
        } else if ($this->session->isAllowed()) {
            return $this->appManager->getById($table, $id);
        } else {
            $this->session->kick('User not allowed to query db');
        }
    }

    public function delete($table, $id) {
        if ($this->session->isAllowed()) {
            $this->appManager->delById($table, $id);
        } else {
            $this->session->kick('User not allowed to delete');
        }
    }

    public function update($table, $news) {
        if ($this->session->isAllowed()) {
            $this->appManager->update($table, $news);
        } else {
            $this->session->kick('User not allowed to update');
        }
    }

    public function save($table, $item, $bypass) {
        if ($this->session->isAllowed() || $bypass) {
            $this->appManager->save($table, $item);
        } else {
            $this->session->kick('User not allowed to add');
        }
    }

    public function count($table) {
        return $this->appManager->count($table);
    }

    public function getCredentials($login, $password) {
        return $this->appManager->login($login, $password);
    }
}
