<?php

namespace Model\Entity;

use PDO;
use PDOException;
use RuntimeException;

abstract class Entity {

    const MODEL = ['users' => User::class, 'news' => News::class];
    private $data = [];

    public function __construct($values = []) {
        if (!empty($values)) {
            $this->hydrate($values);
        }
    }

    public function hydrate($data) {
        foreach ($data as $key => $value) {
            $this->set($key, $value);
        }
    }

    public function __set($name, $value) {
        $this->data[$name] = $value;
    }

    public function __get($name) {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        return null;
    }

    public function get($key) {
        return $this->__get($key);
    }

    public function isNew() {
        return empty($this->get('id'));
    }

    public function addOrUpdate($db, $query, $values)
    {
        if (!is_array($values)) return;
        $request = $db->prepare($query);
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

    public static function fetchData($db, $table, $query, $id = NULL)
    {
        $data = [];
        $class = self::MODEL[$table];
        if (!$class) {
            throw new RuntimeException('unknown data type provided');
        }
        $request = $db->prepare($query);
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
}
