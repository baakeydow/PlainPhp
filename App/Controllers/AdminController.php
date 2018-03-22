<?php
namespace Controllers;

use Model\News;
use PDO;
use DateTime;

class AdminController extends IndexController {

    protected function add(News $news) {
        $request = $this->db->prepare('INSERT INTO news SET author = :author, title = :title, content = :content, dateAdded = NOW(), dateModif = NOW()');

        $request->bindValue(':title', $news->getTitle());
        $request->bindValue(':author', $news->getAuthor());
        $request->bindValue(':content', $news->getContent());

        $request->execute();
    }

    protected function update(News $news) {
        $request = $this->db->prepare('UPDATE news SET author = :author, title = :title, content = :content, dateModif = NOW() WHERE id = :id');

        $request->bindValue(':title', $news->getTitle());
        $request->bindValue(':author', $news->getAuthor());
        $request->bindValue(':content', $news->getContent());
        $request->bindValue(':id', $news->getId(), PDO::PARAM_INT);

        $request->execute();
    }

    public function delete($id) {
        $this->db->exec('DELETE FROM news WHERE id = ' . (int) $id);
    }

    public function count() {
        return $this->db->query('SELECT COUNT(*) FROM news')->fetchColumn();
    }

}
