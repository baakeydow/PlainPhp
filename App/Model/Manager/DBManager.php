<?php

namespace Model\Manager;

use PDO;
use RuntimeException;
use Model\Entity\Entity;
use Model\Entity\News;
use Model\Entity\User;

class DBManager implements ManagerInterface {

    protected $db;

    public function __construct(PDO $database) {
        $this->db = $database;
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

        return Entity::MODEL[$table]::fetchData($this->db, $table, $sql);
    }

    public function getById($table, $id) {
        $sql = 'SELECT * FROM ' . $table . ' WHERE id = :id';
        return Entity::MODEL[$table]::fetchData($this->db, $table, $sql, $id);
    }

    public function delById($table, $id) {
        $this->db->exec('DELETE FROM ' . $table .' WHERE id = ' . (int) $id);
    }

    public function save($item) {
        if ($item->isValid()) {
            $item->isNew() ? $item->add($this->db, $item) : $item->update($this->db, $item);
        } else {
            throw new RuntimeException($item . ' not Valid !');
        }
    }

    public function count($table) {
        return $this->db->query('SELECT COUNT(*) FROM ' . $table)->fetchColumn();
    }
}
