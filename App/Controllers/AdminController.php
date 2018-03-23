<?php
namespace Controllers;

use PDO;
use Model\News\News;
use Model\Users\User;
use Model\Users\UserManager;

class AdminController extends IndexController {

    // news

    public function __construct(PDO $db) {
        parent::__construct($db);
        $this->userManager = new UserManager($db);
    }

    public function delete($id) {
        if ($this->session->isAllowed()) {
            $this->newsManager->delNews($id);
        } else {
            $this->session->kick('User not allowed to delete news');
        }
    }

    public function updateNews(News $news) {
        if ($this->session->isAllowed()) {
            $this->newsManager->updateNews($news);
        } else {
            $this->session->kick('User not allowed to update news');
        }
    }

    public function save(News $news) {
        $this->newsManager->saveNews($news);
    }

    public function countNews() {
        return $this->newsManager->countNews();
    }

    // user

    public function getCredentials($login, $password) {
        return $this->userManager->login($login, $password);
    }

    public function getUsers($start = -1, $limit = -1) {
        return $this->userManager->getUsersList($start, $limit);
    }

    public function getSingleUser($id) {
        if ($this->session->isAllowed()) {
            return $this->userManager->getUserById($id);
        } else {
            $this->session->kick('User not allowed to query users');
        }
    }

    public function delSingleUser($id) {
        if ($this->session->isAllowed()) {
            $this->userManager->delUser($id);
        } else {
            $this->session->kick('User not allowed to delete users');
        }
    }

    public function saveAddedUser(User $user, $bypass = NULL) {
        if ($this->session->isAllowed() || $bypass) {
            $this->userManager->saveUser($user);
        } else {
            $this->session->kick('User not allowed to add new users');
        }
    }

    public function countUsers() {
        return $this->userManager->countUsers();
    }
}
