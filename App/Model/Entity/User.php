<?php

namespace Model\Entity;

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

    public function isValid() {
        return !(empty($this->get('accessLevel')) || empty($this->get('nickname')) || empty($this->get('email')) || empty($this->get('password')));
    }

}
