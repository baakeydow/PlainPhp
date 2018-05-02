<?php

namespace Model\Manager;

use PDO;
use PDOException;
use RuntimeException;
use Model\Entity\News;
use Model\Entity\User;

class DBManager implements ManagerInterface {

    const MODEL = ['users' => User::class, 'news' => News::class];

    protected $db;

    public function __construct(PDO $database) {
        $this->db = $database;
    }

    private function _addOrUpdate($query, $values)
    {
        if (!is_array($values)) return;
        $request = $this->db->prepare($query);
        foreach ($values as $key => $value) {
            $request->bindValue(':' . $key, $value);
            if ($key == 'id') {
                $request->bindValue(':id', $value, PDO::PARAM_INT);
            }
        }
        try {
            $request->execute();
        } catch (PDOException $e) {
            error_log(var_export(debug_backtrace()[1]['function'] . 'Error: ' . $e->getMessage(), true));
        }
    }

    private function _fetchData($class, $query, $id = NULL)
    {
        $data = [];
        if ($class !== 'Model\Entity\User' && $class !== 'Model\Entity\News') {
            throw new RuntimeException('unknown data type provided');
        }
        $request = $this->db->prepare($query);
        $id && $request->bindValue(':id', (int) $id, PDO::PARAM_INT);
        try {
            $request->execute();
            $request->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $class);
            $data =  $id ? $request->fetch() : $request->fetchAll();
            if (!$data) {
                return NULL;
            }
            if (is_array($data)) {
                foreach ($data as $n) {
                    $n->setDates();
                }
            } else {
                $data->setDates();
            }
            $request->closeCursor();
        } catch (PDOException $e) {
            error_log(var_export(debug_backtrace()[1]['function'] . 'Error: ' . $e->getMessage(), true));
        }

        return $data;
    }

    public function login($nickname, $pwd) {
        $sql = 'SELECT * FROM users WHERE nickname = :nickname AND password = :password';
        $request = $this->db->prepare($sql);
        $request->bindValue(':nickname', $nickname);
        $request->bindValue(':password', $pwd);
        $request->execute();
        $request->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, User::class);

        $user = $request->fetch();
        if (!$user) {
            return NULL;
        }
        $request->closeCursor();

        return $user;
    }

    public function getList($table, $start = -1, $limit = -1) {
        $sql = 'SELECT * FROM ' . $table . ' ORDER BY id DESC';

        if ($start != -1 || $limit != -1) {
            $sql .= ' LIMIT ' . (int) $limit . ' OFFSET ' . (int) $start;
        }

        return $this->_fetchData(self::MODEL[$table], $sql);
    }

    public function getById($table, $id) {
        $sql = 'SELECT * FROM ' . $table . ' WHERE id = :id';
        return $this->_fetchData(self::MODEL[$table], $sql, $id);
    }

    public function delById($table, $id) {
        $this->db->exec('DELETE FROM ' . $table .' WHERE id = ' . (int) $id);
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
        $this->_addOrUpdate($sql,
        [
            'nickname' => $user->get('nickname'),
            'email' => $user->get('email'),
            'password' => $user->get('password'),
            'accessLevel' => $user->get('accessLevel')
        ]);
    }

    private function updateUser($user) {
        $sql = 'UPDATE users SET nickname = :nickname, email = :email, password = :password, accessLevel = :accessLevel, lastAccess = NOW() WHERE id = :id';
        $this->_addOrUpdate($sql,
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
        $this->_addOrUpdate($sql,
        [
            'title' => $news->get('title'),
            'author' => $news->get('author'),
            'content' => $news->get('content'),
        ]);
    }

    private function updateNews($news) {
        $sql = 'UPDATE news SET author = :author, title = :title, content = :content, dateModif = NOW() WHERE id = :id';
        $this->_addOrUpdate($sql,
        [
            'title' => $news->get('title'),
            'author' => $news->get('author'),
            'content' => $news->get('content'),
            'id' => $news->get('id')
        ]);
    }

    public function count($table) {
        return $this->db->query('SELECT COUNT(*) FROM ' . $table)->fetchColumn();
    }
}
