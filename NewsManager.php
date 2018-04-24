<?php
namespace Model\News;

use PDO;
use RuntimeException;
use Model\DB\DBManager;
use Model\ManagerInterface;

class NewsManager implements ManagerInterface {

    protected $DBManager;

    public function __construct(PDO $db) {
        $this->DBManager = new DBManager($db);
    }

    public function getList($start = -1, $limit = -1) {
        $sql = 'SELECT id, author, title, content, dateAdded, dateModif FROM news ORDER BY id DESC';

        if ($start != -1 || $limit != -1) {
            $sql .= ' LIMIT ' . (int) $limit . ' OFFSET ' . (int) $start;
        }

        return $this->DBManager->fetchData(News::class, $sql);
    }

    public function getById($id) {
        $sql = 'SELECT id, author, title, content, dateAdded, dateModif FROM news WHERE id = :id';

        return $this->DBManager->fetchData(News::class, $sql, $id);
    }

    public function delById($id) {
        $this->DBManager->delById('news', $id);
    }

    public function save($news) {
        if ($news->isValid()) {
            $news->isNew() ? $this->add($news) : $this->update($news);
        } else {
            throw new RuntimeException('News not Valid');
        }
    }

    public function add($news) {
        $sql = 'INSERT INTO news SET author = :author, title = :title, content = :content, dateAdded = NOW(), dateModif = NOW()';
        $this->DBManager->addOrUpdate($sql,
        [
            'title' => $news->getTitle(),
            'author' => $news->getAuthor(),
            'content' => $news->getContent(),
        ]);
    }

    public function update($news) {
        $sql = 'UPDATE news SET author = :author, title = :title, content = :content, dateModif = NOW() WHERE id = :id';
        $this->DBManager->addOrUpdate($sql,
        [
            'title' => $news->getTitle(),
            'author' => $news->getAuthor(),
            'content' => $news->getContent(),
            'id' => $news->getId()
        ]);
    }

    public function count() {
        return $this->DBManager->count('news');
    }
}
