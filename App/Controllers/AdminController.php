<?php
namespace Controllers;

use PDO;
use Lib\Page;
use Model\News\News;
use Model\Users\User;
use Model\Users\UserManager;

class AdminController extends IndexController {

    public function __construct(PDO $db) {
        parent::__construct($db);
        $this->page = new Page('admin');
        $this->userManager = new UserManager($db);
    }

    public function index() {
        $this->page->addVar('Ctrl', $this);
        // news
        if (isset($_GET['modif'])) {
            $news = $this->getOne((int) $_GET['modif']);
            if (!$news) {
                $this->session->kick('news not found');
            }
            $this->page->addVar('news', $news);
        }
        if (isset($_GET['delete'])) {
            if (isset($_SESSION['level']) && $_SESSION['level'] == '1') {
                $this->delete((int) $_GET['delete']);
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
                $news->isNew() ? $this->save($news) : $this->updateNews($news);
                $message = $news->isNew() ? 'The news has been added !' : 'The news has been modified !';
                $this->page->addVar('message', $message);
                unset($news);
            } else {
                $this->page->addVar('errors', $news->getErrors());
            }
        }
        // user
        if (isset($_GET['editUser'])) {
            $user = $this->getSingleUser((int) $_GET['editUser']);
            if (!$user) {
                $this->session->kick('User not found');
            }
            $this->page->addVar('user', $user);
        }
        if (isset($_GET['delUser'])) {
            $this->delSingleUser((int) $_GET['delUser']);
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
                $this->saveAddedUser($user);
                $userNotice = $user->isNew() ? 'User has just been added !' : 'User has just been modified !';
                $this->page->addVar('userNotice', $userNotice);
                unset($user);
            } else {
                $this->page->addVar('errors', $user->getErrors());
            }
        }
        $this->page->send();
    }

    // news

    public function delete($id) {
        if ($this->session->isAllowed()) {
            $this->newsManager->delById($id);
        } else {
            $this->session->kick('User not allowed to delete news');
        }
    }

    public function updateNews(News $news) {
        if ($this->session->isAllowed()) {
            $this->newsManager->update($news);
        } else {
            $this->session->kick('User not allowed to update news');
        }
    }

    public function save(News $news) {
        $this->newsManager->save($news);
    }

    public function countNews() {
        return $this->newsManager->count();
    }

    // user

    public function getCredentials($login, $password) {
        return $this->userManager->login($login, $password);
    }

    public function getUsers($start = -1, $limit = -1) {
        return $this->userManager->getList($start, $limit);
    }

    public function getSingleUser($id) {
        if ($this->session->isAllowed()) {
            return $this->userManager->getById($id);
        } else {
            $this->session->kick('User not allowed to query users');
        }
    }

    public function delSingleUser($id) {
        if ($this->session->isAllowed()) {
            $this->userManager->delById($id);
        } else {
            $this->session->kick('User not allowed to delete users');
        }
    }

    public function saveAddedUser(User $user, $bypass = NULL) {
        if ($this->session->isAllowed() || $bypass) {
            $this->userManager->save($user);
        } else {
            $this->session->kick('User not allowed to add new users');
        }
    }

    public function countUsers() {
        return $this->userManager->count();
    }
}
