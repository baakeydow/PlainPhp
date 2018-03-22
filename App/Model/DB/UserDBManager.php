<?php
namespace Model\DB;

use PDO;
use PDOException;
use DateTime;

class UserDBManager extends DBManager
{
    public function fetchAllUsers($class, $query)
    {
        $request = $this->db->query($query);
        $request->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $class);
        $usersList = $request->fetchAll();

        foreach ($usersList as $user) {
            $user->setDateCreated(new DateTime($user->getDateCreated()));
            $user->setLastAccess(new DateTime($user->getLastAccess()));
        }

        $request->closeCursor();

        return $usersList;
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

    public function fetchOne($class, $query, $id)
    {
        $request = $this->db->prepare($query);
        $request->bindValue(':id', (int) $id, PDO::PARAM_INT);
        $request->execute();
        $request->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, $class);

        $user = $request->fetch();
        if (!$user) {
            return NULL;
        }
        $user->setDateCreated(new DateTime($user->getDateCreated()));
        $user->setLastAccess(new DateTime($user->getLastAccess()));

        $request->closeCursor();

        return $user;
    }

    public function addOne($query, $nickname, $email, $password, $accessLevel)
    {
        $request = $this->db->prepare($query);
        $request->bindValue(':nickname', $nickname);
        $request->bindValue(':email', $email);
        $request->bindValue(':password', $password);
        $request->bindValue(':accessLevel', $accessLevel);
        try {
            $request->execute();
        } catch (PDOException $e) {
            error_log(var_export('User already exists !' . $e->getMessage(), true));
        }
    }

    public function updateOne($query, $nickname, $email, $password, $accessLevel, $id)
    {
        $request = $this->db->prepare($query);
        $request->bindValue(':nickname', $nickname);
        $request->bindValue(':email', $email);
        $request->bindValue(':password', $password);
        $request->bindValue(':accessLevel', $accessLevel);
        $request->bindValue(':id', $id, PDO::PARAM_INT);
        try {
            $request->execute();
        } catch (PDOException $e) {
            error_log(var_export('User was not Updated !' . $e->getMessage(), true));
        }
    }
}
