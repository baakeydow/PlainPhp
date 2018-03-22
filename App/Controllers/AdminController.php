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
        $this->newsManager->delNews($id);
    }

    public function save(News $news) {
        $this->newsManager->saveNews($news);
    }

    public function update(News $news) {
        $this->newsManager->updateNews($news);
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
        return $this->userManager->getUserById($id);
    }

    public function delSingleUser($id) {
        return $this->userManager->delUser($id);
    }

    public function saveAddedUser(User $user) {
        return $this->userManager->saveUser($user);
    }

    public function countUsers() {
        return $this->userManager->countUsers();
    }
}
