<?php
namespace Model\News;

use PDO;
use Model\DB\NewsDBManager;

class NewsManager implements NewsManagerInterface {

    protected $DBManager;

    public function __construct(PDO $db) {
        $this->DBManager = new NewsDBManager($db);
    }

    public function getNewsList($start = -1, $limit = -1) {
        $sql = 'SELECT id, author, title, content, dateAdded, dateModif FROM news ORDER BY id DESC';

        if ($start != -1 || $limit != -1) {
            $sql .= ' LIMIT ' . (int) $limit . ' OFFSET ' . (int) $start;
        }

        return $this->DBManager->fetchAllNews(News::class, $sql);
    }

    public function getNewsById($id) {
        $sql = 'SELECT id, author, title, content, dateAdded, dateModif FROM news WHERE id = :id';

        return $this->DBManager->fetchOne(News::class, $sql, $id);
    }

    public function delNews($id) {
        $this->DBManager->delById('news', $id);
    }

    public function saveNews(News $news) {
        if ($news->isValid()) {
            $news->isNew() ? $this->addNews($news) : $this->updateNews($news);
        } else {
            throw new RuntimeException('News not Valid');
        }
    }

    public function addNews(News $news) {
        $sql = 'INSERT INTO news SET author = :author, title = :title, content = :content, dateAdded = NOW(), dateModif = NOW()';
        $this->DBManager->addOne($sql, $news->getTitle(), $news->getAuthor(), $news->getContent());
    }

    public function updateNews(News $news) {
        $sql = 'UPDATE news SET author = :author, title = :title, content = :content, dateModif = NOW() WHERE id = :id';
        $this->DBManager->updateOne($sql, $news->getTitle(), $news->getAuthor(), $news->getContent(), $news->getId());
    }

    public function countNews() {
        return $this->DBManager->count('news');
    }
}
