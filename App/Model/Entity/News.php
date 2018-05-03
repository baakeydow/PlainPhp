<?php

namespace Model\Entity;

use DateTime;
use Model\Entity\Entity;

class News extends Entity
{
    const INVALID_TITLE = 4;
    const INVALID_CONTENT = 5;

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

    public function setDates() {
        $this->set('dateAdded', new DateTime($this->get('dateAdded')));
        $this->set('dateModif', new DateTime($this->get('dateModif')));
    }

    public function isValid() {
        return !(empty($this->get('author')) || empty($this->get('title')) || empty($this->get('content')));
    }

    public function add($db, $news) {
        $sql = 'INSERT INTO news SET author = :author, title = :title, content = :content, dateAdded = NOW(), dateModif = NOW()';
        $this->addOrUpdate($db, $sql,
        [
            'title' => $news->get('title'),
            'author' => $news->get('author'),
            'content' => $news->get('content'),
        ]);
    }

    public function update($db, $news) {
        $sql = 'UPDATE news SET author = :author, title = :title, content = :content, dateModif = NOW() WHERE id = :id';
        $this->addOrUpdate($db, $sql,
        [
            'title' => $news->get('title'),
            'author' => $news->get('author'),
            'content' => $news->get('content'),
            'id' => $news->get('id')
        ]);
    }
}
