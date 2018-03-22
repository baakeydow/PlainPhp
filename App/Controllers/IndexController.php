<?php
namespace Controllers;

use Model\NewsManager;
use Model\News;
use PDO;
use DateTime;

class IndexController extends NewsManager {

    protected $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function getList($start = -1, $limit = -1) {
        $sql = 'SELECT id, author, title, content, dateAdded, dateModif FROM news ORDER BY id DESC';

        if ($start != -1 || $limit != -1) {
            $sql .= ' LIMIT ' . (int) $limit . ' OFFSET ' . (int) $start;
        }

        $request = $this->db->query($sql);
        $request->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Model\News');

        $newsList = $request->fetchAll();

        foreach ($newsList as $news) {
            $news->setDateAdded(new DateTime($news->getDateAdded()));
            $news->setDateModified(new DateTime($news->getDateModified()));
        }

        $request->closeCursor();

        return $newsList;
    }

    public function getOne($id) {
        $request = $this->db->prepare('SELECT id, author, title, content, dateAdded, dateModif FROM news WHERE id = :id');
        $request->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $request->execute();

        $request->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Model\News');

        $news = $request->fetch();

        $news->setDateAdded(new DateTime($news->getDateAdded()));
        $news->setDateModified(new DateTime($news->getDateModified()));

        return $news;
    }

    protected function add(News $news) {

    }

    protected function update(News $news) {

    }

    public function delete($id) {

    }

    public function count() {

    }

}
