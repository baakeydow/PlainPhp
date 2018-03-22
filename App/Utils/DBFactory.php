<?php
namespace Utils;

use PDO;

class DBFactory {

    public static function getMysqlConnexionWithPDO() {
        $db = new PDO('mysql:host=localhost;dbname=news', 'news', 'newspass');
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $db;
    }

}
