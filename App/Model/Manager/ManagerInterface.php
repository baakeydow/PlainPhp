<?php

namespace Model\Manager;

interface ManagerInterface {

    public function getList($table, $start = -1, $limit = -1);

    public function getById($table, $id);

    public function delById($table, $id);

    public function save($table, $item);

    public function count($table);
}
