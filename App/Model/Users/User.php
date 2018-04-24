<?php

namespace Model\Users;

use DateTime;

class User {

    protected $data = [];

    const INVALID_NAME = 1;
    const INVALID_EMAIL = 2;
    const INVALID_PWD = 3;

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

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->data)) {
            return $this->data[$name];
        }
        return null;
    }

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

    public function get($key) {
        return $this->__get($key);
    }

    public function isNew() {
        return empty($this->get('id'));
    }

    public function isValid() {
        return !(empty($this->get('accessLevel')) || empty($this->get('nickname')) || empty($this->get('email')) || empty($this->get('password')));
    }

}
