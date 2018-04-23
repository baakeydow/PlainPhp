<?php

namespace Model;

interface ManagerInterface {

    public function getList($start = -1, $limit = -1);

    public function getById($id);

    public function delById($id);

    public function save($item);

    public function add($item);

    public function update($item);

    public function count();
}
