<?php

namespace Model\Users;

use DateTime;

class User {

    protected $errors = [],
            $id,
            $nickname,
            $email,
            $password,
            $accessLevel,
            $creationDate,
            $lastAccess;

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
            $method = 'set' . ucfirst($key);

            if (is_callable([$this, $method])) {
                $this->$method($value);
            }
        }
    }

    public function isNew() {
        return empty($this->id);
    }

    public function getAccessLevel() {
        return $this->accessLevel;
    }

    public function isValid() {
        return !(empty($this->accessLevel) || empty($this->nickname) || empty($this->email) || empty($this->password) || empty($this->accessLevel));
    }

    public function getErrors() {
        return $this->errors;
    }

    public function getId() {
        return $this->id;
    }

    public function getNickName() {
        return $this->nickname;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getPWD() {
        return $this->password;
    }

    public function getDateCreated() {
        return $this->creationDate;
    }

    public function getLastAccess() {
        return $this->lastAccess;
    }

    public function setId($id) {
        $this->id = (int) $id;
    }

    public function setNickname($nickname) {
        if (!is_string($nickname) || empty($nickname)) {
            $this->errors[] = self::INVALID_NAME;
        } else {
            $this->nickname = $nickname;
        }
    }

    public function setEmail($email) {
        if (!is_string($email) || empty($email)) {
            $this->errors[] = self::INVALID_EMAIL;
        } else {
            $this->email = $email;
        }
    }

    public function setPassword($password) {
        if (empty($password)) {
            $this->errors[] = self::INVALID_PWD;
        } else {
            $this->password = $password;
        }
    }

    public function setAccesslevel($accessLevel) {
        $this->accessLevel = !empty($accessLevel) && $accessLevel === 'true' ? 1 : 2;
    }

    public function setDateCreated(DateTime $creationDate) {
        $this->creationDate = $creationDate;
    }

    public function setLastAccess(DateTime $lastAccess) {
        $this->lastAccess = $lastAccess;
    }

}
