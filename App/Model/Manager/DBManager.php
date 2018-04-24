<?php
namespace Model\Manager;

use PDO;
use PDOException;
use RuntimeException;
use DateTime;

class DBManager
{
    protected $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    public function fetchLogin($class, $query, $nickname, $pwd)
    {
        $request = $this->db->prepare($query);
        $request->bindValue(':nickname', $nickname);
        $request->bindValue(':password', $pwd);
        $request->execute();
        $request->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $class);

        $user = $request->fetch();
        if (!$user) {
            return NULL;
        }
        $request->closeCursor();

        return $user;
    }

    public function fetchData($class, $query, $id = NULL)
    {
        if ($class !== 'Model\Users\User' && $class !== 'Model\News\News') {
            throw new RuntimeException('unknown data type provided');
        }
        $request = $this->db->prepare($query);
        $id && $request->bindValue(':id', (int) $id, PDO::PARAM_INT);
        try {
            $request->execute();
            $request->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $class);
            $data =  $id ? $request->fetch() : $request->fetchAll();
            if (!$data) {
                return NULL;
            }
            if (is_array($data)) {
                foreach ($data as $n) {
                    if ($class === 'Model\Users\User') {
                        $n->set('creationDate', new DateTime($n->get('creationDate')));
                        $n->set('lastAccess', new DateTime($n->get('lastAccess')));
                    } else {
                        $n->set('dateAdded', new DateTime($n->get('dateAdded')));
                        $n->set('dateModif', new DateTime($n->get('dateModif')));
                    }
                }
            } else {
                if ($class === 'Model\Users\User') {
                    $data->set('creationDate', new DateTime($data->get('creationDate')));
                    $data->set('lastAccess', new DateTime($data->get('lastAccess')));
                } else {
                    $data->set('dateAdded', new DateTime($data->get('dateAdded')));
                    $data->set('dateModif', new DateTime($data->get('dateModif')));
                }
            }
            $request->closeCursor();
        } catch (PDOException $e) {
            error_log(var_export(debug_backtrace()[1]['function'] . 'Error: ' . $e->getMessage(), true));
        }

        return $data;
    }

    public function addOrUpdate($query, $values)
    {
        if (!is_array($values)) return;
        $request = $this->db->prepare($query);
        foreach ($values as $key => $value) {
            $request->bindValue(':' . $key, $value);
            if ($key == 'id') {
                $request->bindValue(':id', $value, PDO::PARAM_INT);
            }
        }
        try {
            $request->execute();
        } catch (PDOException $e) {
            error_log(var_export(debug_backtrace()[1]['function'] . 'Error: ' . $e->getMessage(), true));
        }
    }

    public function delById($table, $id)
    {
        $this->db->exec('DELETE FROM ' . $table .' WHERE id = ' . (int) $id);
    }

    public function count($table)
    {
        return $this->db->query('SELECT COUNT(*) FROM ' . $table)->fetchColumn();
    }
}
