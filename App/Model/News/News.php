<?php

namespace Model\News;

use DateTime;

class News {

    private $data = [];

    const INVALID_TITLE = 1;
    const INVALID_CONTENT = 2;

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
            case 'title':
                if (!is_string($value) || empty($value)) {
                    $this->__set('error', self::INVALID_TITLE);
                } else {
                    $this->__set($key, $value);
                }
                break;
            case 'content':
                if (!is_string($value) || empty($value)) {
                    $this->__set('error', self::INVALID_CONTENT);
                } else {
                    $this->__set($key, $value);
                }
                break;
            case 'author':
                $this->__set($key, $_SESSION['user']);
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
        return !(empty($this->get('author')) || empty($this->get('title')) || empty($this->get('content')));
    }

}
