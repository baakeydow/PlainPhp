<?php
namespace Controller;

use PDO;
use Lib\HTTPRequest;
use Model\Manager\DBManager;

class MainController {

    protected $dbManager;

    public function __construct(PDO $db, $session)
    {
        $this->session = $session;
        $this->dbManager = new DBManager($db);
    }

    public function getThem($table , $start = -1, $limit = -1) {
        return $this->dbManager->getList($table, $start, $limit);
    }

    public function getOne($table, $id) {
        if ($table == 'news') {
            return $this->dbManager->getById($table, $id);
        } else if ($this->session->isAllowed()) {
            return $this->dbManager->getById($table, $id);
        } else {
            $this->session->kick('User not allowed to query db');
        }
    }

    public function delete($table, $id) {
        if ($this->session->isAllowed()) {
            $this->dbManager->delById($table, $id);
        } else {
            $this->session->kick('User not allowed to delete');
        }
    }

    public function save($table, $item, $bypass) {
        if ($this->session->isAllowed() || $bypass) {
            $this->dbManager->save($table, $item);
        } else {
            $this->session->kick('User not allowed to add');
        }
    }

    public function count($table) {
        return $this->dbManager->count($table);
    }

    public function login(HTTPRequest $request)
    {
        if (!$request->postExists('username')) {
            return false;
        }
        $login = $request->postData('username');
        $password = $request->postData('passwd');
        $user = $this->getCredentials($login, $password);
        if ($user) {
            $this->session->setAuthenticated(true, $user);
            $this->save('users', $user, 'login');
            return true;
        }
        $this->session->setFlash('Username or Password Invalid ! try again...');
        return false;
    }

    public function getCredentials($login, $password) {
        return $this->dbManager->login($login, $password);
    }
}
