<?php

namespace Model\Entity;

use DateTime;
use Model\Entity\Entity;

class User extends Entity
{
    const INVALID_NAME = 1;
    const INVALID_EMAIL = 2;
    const INVALID_PWD = 3;

    public function set($key, $value) {
        switch ($key) {
            case 'nickname':
                if (!is_string($value) || empty($value)) {
                    $this->__set('error', self::INVALID_NAME);
                } else {
                    $this->__set($key, $value);
                }
                break;
            case 'email':
                if (!is_string($value) || empty($value)) {
                    $this->__set('error', self::INVALID_EMAIL);
                } else {
                    $this->__set($key, $value);
                }
                break;
            case 'password':
                if (!is_string($value) || empty($value)) {
                    $this->__set('error', self::INVALID_PWD);
                } else {
                    $this->__set($key, $value);
                }
                break;
            case 'accessLevel':
                $lvl = !empty($value) && $value === 'true' ? 1 : 2;
                $this->__set($key, $lvl);
                break;
            default:
                if (is_string($key) && !empty($key) && !empty($value)) {
                    $this->__set($key, $value);
                }
                break;
        }
    }

    public function setDates() {
        $this->set('creationDate', new DateTime($this->get('creationDate')));
        $this->set('lastAccess', new DateTime($this->get('lastAccess')));
    }

    public function isValid() {
        return !(empty($this->get('accessLevel')) || empty($this->get('nickname')) || empty($this->get('email')) || empty($this->get('password')));
    }

    public function add($db, $user) {
        $sql = 'INSERT INTO users SET nickname = :nickname, email = :email, password = :password, accessLevel = :accessLevel, creationDate = NOW(), lastAccess = NOW()';
        $this->addOrUpdate($db, $sql,
        [
            'nickname' => $user->get('nickname'),
            'email' => $user->get('email'),
            'password' => $user->get('password'),
            'accessLevel' => $user->get('accessLevel')
        ]);
    }

    public function update($db, $user) {
        $sql = 'UPDATE users SET nickname = :nickname, email = :email, password = :password, accessLevel = :accessLevel, lastAccess = NOW() WHERE id = :id';
        $this->addOrUpdate($db, $sql,
        [
            'nickname' => $user->get('nickname'),
            'email' => $user->get('email'),
            'password' => $user->get('password'),
            'accessLevel' => $user->get('accessLevel'),
            'id' => $user->get('id'),
        ]);
    }
}
