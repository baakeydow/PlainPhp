<?php
namespace Model\DB;

use PDO;
use PDOException;
use DateTime;

class NewsDBManager extends DBManager
{
    public function fetchNewsData($class, $query, $id = NULL)
    {
        $request = $this->db->prepare($query);
        $id && $request->bindValue(':id', (int) $id, PDO::PARAM_INT);
        try {
            $request->execute();
            $request->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $class);
            $news =  $id ? $request->fetch() : $request->fetchAll();
            if (!$news) {
                return NULL;
            }
            if (is_array($news)) {
                foreach ($news as $n) {
                    $n->setDateAdded(new DateTime($n->getDateAdded()));
                    $n->setDateModified(new DateTime($n->getDateModified()));
                }
            } else {
                $news->setDateAdded(new DateTime($news->getDateAdded()));
                $news->setDateModified(new DateTime($news->getDateModified()));
            }
            $request->closeCursor();
        } catch (PDOException $e) {
            error_log(var_export(debug_backtrace()[1]['function'] . 'Error: ' . $e->getMessage(), true));
        }

        return $news;
    }
}
