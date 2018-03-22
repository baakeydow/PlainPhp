<?php
namespace Model\DB;

use PDO;
use PDOException;
use DateTime;

class NewsDBManager extends DBManager
{
    public function fetchAllNews($class, $query)
    {
        $request = $this->db->query($query);
        $request->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $class);
        $newsList = $request->fetchAll();

        foreach ($newsList as $news) {
            $news->setDateAdded(new DateTime($news->getDateAdded()));
            $news->setDateModified(new DateTime($news->getDateModified()));
        }

        $request->closeCursor();

        return $newsList;
    }

    public function fetchOne($class, $query, $id)
    {
        $request = $this->db->prepare($query);
        $request->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $request->execute();
        $request->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $class);

        $news = $request->fetch();
        if (!$news) {
            return NULL;
        }
        $news->setDateAdded(new DateTime($news->getDateAdded()));
        $news->setDateModified(new DateTime($news->getDateModified()));

        $request->closeCursor();

        return $news;
    }

    public function addOne($query, $title, $author, $content)
    {
        $request = $this->db->prepare($query);
        $request->bindValue(':title', $title);
        $request->bindValue(':author', $author);
        $request->bindValue(':content', $content);
        try {
            $request->execute();
        } catch (PDOException $e) {
            error_log(var_export('News was not added !' . $e->getMessage(), true));
        }
    }

    public function updateOne($query, $title, $author, $content, $id)
    {
        $request = $this->db->prepare($query);
        $request->bindValue(':title', $title);
        $request->bindValue(':author', $author);
        $request->bindValue(':content', $content);
        $request->bindValue(':id', $id, PDO::PARAM_INT);
        try {
            $request->execute();
        } catch (PDOException $e) {
            error_log(var_export('News was not updated !' . $e->getMessage(), true));
        }
    }
}
