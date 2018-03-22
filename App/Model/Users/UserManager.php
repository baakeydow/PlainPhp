<?php

namespace Model\Users;

use PDO;
use Model\DB\UserDBManager;

class UserManager implements UserManagerInterface {

    protected $DBManager;

    public function __construct(PDO $db) {
        $this->DBManager = new UserDBManager($db);
    }

    public function login($nickname, $pwd) {
        $sql = 'SELECT * FROM users WHERE nickname = :nickname AND password = :password';
        return $this->DBManager->fetchLogin(User::class, $sql, $nickname, $pwd);
    }

    public function getUsersList($start = -1, $limit = -1) {
        $sql = 'SELECT * FROM users ORDER BY id DESC';

        if ($start != -1 || $limit != -1) {
            $sql .= ' LIMIT ' . (int) $limit . ' OFFSET ' . (int) $start;
        }

        return $this->DBManager->fetchAllUsers(User::class, $sql);
    }

    public function getUserById($id) {
        $sql = 'SELECT * FROM users WHERE id = :id';
        return $this->DBManager->fetchOne(User::class, $sql, $id);
    }

    public function delUser($id) {
        $this->DBManager->delById('users', $id);
    }

    public function saveUser(User $user) {
        if ($user->isValid()) {
            $user->isNew() ? $this->addUser($user) : $this->updateUser($user);
        } else {
            throw new RuntimeException('User not Valid');
        }
    }

    public function addUser(User $user) {
        $sql = 'INSERT INTO users SET nickname = :nickname, email = :email, password = :password, accessLevel = :accessLevel, creationDate = NOW(), lastAccess = NOW()';
        $this->DBManager->addOne($sql, $user->getNickName(), $user->getEmail(), $user->getPWD(), $user->getAccessLevel());
    }

    public function updateUser(User $user) {
        $sql = 'UPDATE users SET nickname = :nickname, email = :email, password = :password, accessLevel = :accessLevel, lastAccess = NOW() WHERE id = :id';
        $this->DBManager->updateOne($sql, $user->getNickName(), $user->getEmail(), $user->getPWD(), $user->getAccessLevel(), $user->getId());
    }

    public function countUsers() {
        return $this->DBManager->count('users');
    }
}
