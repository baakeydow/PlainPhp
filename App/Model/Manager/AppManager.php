<?php

namespace Model\Manager;

use PDO;
use Model\News\News;
use Model\Users\User;

class AppManager implements ManagerInterface {

    const MODEL = ['users' => User::class, 'news' => News::class];

    protected $DBManager;

    public function __construct(PDO $db) {
        $this->DBManager = new DBManager($db);
    }

    public function login($nickname, $pwd) {
        $sql = 'SELECT * FROM users WHERE nickname = :nickname AND password = :password';
        return $this->DBManager->fetchLogin(User::class, $sql, $nickname, $pwd);
    }

    public function getList($table, $start = -1, $limit = -1) {
        $sql = 'SELECT * FROM ' . $table . ' ORDER BY id DESC';

        if ($start != -1 || $limit != -1) {
            $sql .= ' LIMIT ' . (int) $limit . ' OFFSET ' . (int) $start;
        }

        return $this->DBManager->fetchData(self::MODEL[$table], $sql);
    }

    public function getById($table, $id) {
        $sql = 'SELECT * FROM ' . $table . ' WHERE id = :id';
        return $this->DBManager->fetchData(self::MODEL[$table], $sql, $id);
    }

    public function delById($table, $id) {
        $this->DBManager->delById($table, $id);
    }

    public function save($table, $item) {
        if ($item->isValid()) {
            if ($table == 'users') {
                $item->isNew() ? $this->addUser($item) : $this->updateUser($item);
            } else if ($table == 'news') {
                $item->isNew() ? $this->addNews($item) : $this->updateNews($item);
            }
        } else {
            throw new RuntimeException($item . ' not Valid !');
        }
    }

    private function addUser($user) {
        $sql = 'INSERT INTO users SET nickname = :nickname, email = :email, password = :password, accessLevel = :accessLevel, creationDate = NOW(), lastAccess = NOW()';
        $this->DBManager->addOrUpdate($sql,
        [
            'nickname' => $user->get('nickname'),
            'email' => $user->get('email'),
            'password' => $user->get('password'),
            'accessLevel' => $user->get('accessLevel')
        ]);
    }

    private function updateUser($user) {
        $sql = 'UPDATE users SET nickname = :nickname, email = :email, password = :password, accessLevel = :accessLevel, lastAccess = NOW() WHERE id = :id';
        $this->DBManager->addOrUpdate($sql,
        [
            'nickname' => $user->get('nickname'),
            'email' => $user->get('email'),
            'password' => $user->get('password'),
            'accessLevel' => $user->get('accessLevel'),
            'id' => $user->get('id'),
        ]);
    }

    private function addNews($news) {
        $sql = 'INSERT INTO news SET author = :author, title = :title, content = :content, dateAdded = NOW(), dateModif = NOW()';
        $this->DBManager->addOrUpdate($sql,
        [
            'title' => $news->get('title'),
            'author' => $news->get('author'),
            'content' => $news->get('content'),
        ]);
    }

    private function updateNews($news) {
        $sql = 'UPDATE news SET author = :author, title = :title, content = :content, dateModif = NOW() WHERE id = :id';
        $this->DBManager->addOrUpdate($sql,
        [
            'title' => $news->get('title'),
            'author' => $news->get('author'),
            'content' => $news->get('content'),
            'id' => $news->get('id')
        ]);
    }

    public function count($table) {
        return $this->DBManager->count($table);
    }
}
