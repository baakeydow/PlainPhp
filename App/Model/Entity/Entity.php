<?php

namespace Model\Entity;

class Entity {

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

}
