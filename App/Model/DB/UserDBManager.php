<?php
namespace Model\DB;

use PDO;
use PDOException;
use DateTime;

class UserDBManager extends DBManager
{
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

    public function fetchUsersData($class, $query, $id = NULL)
    {
        $request = $this->db->prepare($query);
        $id && $request->bindValue(':id', (int) $id, PDO::PARAM_INT);
        try {
            $request->execute();
            $request->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $class);
            $user =  $id ? $request->fetch() : $request->fetchAll();
            if (!$user) {
                return NULL;
            }
            if (is_array($user)) {
                foreach ($user as $n) {
                    $n->setDateCreated(new DateTime($n->getDateCreated()));
                    $n->setLastAccess(new DateTime($n->getLastAccess()));
                }
            } else {
                $user->setDateCreated(new DateTime($user->getDateCreated()));
                $user->setLastAccess(new DateTime($user->getLastAccess()));
            }
            $request->closeCursor();
        } catch (PDOException $e) {
            error_log(var_export(debug_backtrace()[1]['function'] . 'Error: ' . $e->getMessage(), true));
        }

        return $user;
    }

}
