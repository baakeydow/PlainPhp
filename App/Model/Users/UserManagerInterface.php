<?php

namespace Model\Users;

interface UserManagerInterface {

    public function getUsersList($debut = -1, $limite = -1);

    public function getUserById($id);

    public function delUser($id);

    public function saveUser(User $user);

    public function addUser(User $user);

    public function updateUser(User $user);

    public function countUsers();
}
