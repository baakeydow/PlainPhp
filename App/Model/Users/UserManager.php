<?php

namespace Model\Users;

use PDO;
use Model\DB\DBManager;
use Model\ManagerInterface;

class UserManager implements ManagerInterface {

    protected $DBManager;

    public function __construct(PDO $db) {
        $this->DBManager = new DBManager($db);
    }

    public function login($nickname, $pwd) {
        $sql = 'SELECT * FROM users WHERE nickname = :nickname AND password = :password';
        return $this->DBManager->fetchLogin(User::class, $sql, $nickname, $pwd);
    }

    public function getList($start = -1, $limit = -1) {
        $sql = 'SELECT * FROM users ORDER BY id DESC';

        if ($start != -1 || $limit != -1) {
            $sql .= ' LIMIT ' . (int) $limit . ' OFFSET ' . (int) $start;
        }

        return $this->DBManager->fetchData(User::class, $sql);
    }

    public function getById($id) {
        $sql = 'SELECT * FROM users WHERE id = :id';
        return $this->DBManager->fetchData(User::class, $sql, $id);
    }

    public function delById($id) {
        $this->DBManager->delById('users', $id);
    }

    public function save($user) {
        if ($user->isValid()) {
            $user->isNew() ? $this->add($user) : $this->update($user);
        } else {
            throw new RuntimeException('User not Valid');
        }
    }

    public function add($user) {
        $sql = 'INSERT INTO users SET nickname = :nickname, email = :email, password = :password, accessLevel = :accessLevel, creationDate = NOW(), lastAccess = NOW()';
        $this->DBManager->addOrUpdate($sql,
        [
            'nickname' => $user->getNickName(),
            'email' => $user->getEmail(),
            'password' => $user->getPWD(),
            'accessLevel' => $user->getAccessLevel()
        ]);
    }

    public function update($user) {
        $sql = 'UPDATE users SET nickname = :nickname, email = :email, password = :password, accessLevel = :accessLevel, lastAccess = NOW() WHERE id = :id';
        $this->DBManager->addOrUpdate($sql,
        [
            'nickname' => $user->getNickName(),
            'email' => $user->getEmail(),
            'password' => $user->getPWD(),
            'accessLevel' => $user->getAccessLevel(),
            'id' => $user->getId(),
        ]);
    }

    public function count() {
        return $this->DBManager->count('users');
    }
}
