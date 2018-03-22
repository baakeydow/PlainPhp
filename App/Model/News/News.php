<?php

namespace Model\News;

use DateTime;

class News {

    protected $errors = [],
            $id,
            $author,
            $title,
            $content,
            $dateAdded,
            $dateModif;

    const INVALID_AUTHOR = 1;
    const INVALID_TITLE = 2;
    const INVALID_CONTENT = 3;

    public function __construct($values = []) {
        if (!empty($values)) {
            $this->hydrate($values);
        }
    }

    public function hydrate($data) {
        foreach ($data as $key => $value) {
            $methode = 'set' . ucfirst($key);

            if (is_callable([$this, $methode])) {
                $this->$methode($value);
            }
        }
    }

    public function isNew() {
        return empty($this->id);
    }

    public function isValid() {
        return !(empty($this->author) || empty($this->title) || empty($this->content));
    }

    public function getErrors() {
        return $this->errors;
    }

    public function getId() {
        return $this->id;
    }

    public function getAuthor() {
        return $this->author;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getContent() {
        return $this->content;
    }

    public function getDateAdded() {
        return $this->dateAdded;
    }

    public function getDateModified() {
        return $this->dateModif;
    }

    public function setId($id) {
        $this->id = (int) $id;
    }

    public function setAuthor($author) {
        $this->author = $_SESSION['user'];
    }

    public function setTitle($title) {
        if (!is_string($title) || empty($title)) {
            $this->errors[] = self::INVALID_TITLE;
        } else {
            $this->title = $title;
        }
    }

    public function setContent($content) {
        if (!is_string($content) || empty($content)) {
            $this->errors[] = self::INVALID_CONTENT;
        } else {
            $this->content = $content;
        }
    }

    public function setDateAdded(DateTime $dateAdded) {
        $this->dateAdded = $dateAdded;
    }

    public function setDateModified(DateTime $dateModif) {
        $this->dateModif = $dateModif;
    }

}
