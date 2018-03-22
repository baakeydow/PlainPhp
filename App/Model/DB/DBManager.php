<?php
namespace Model\DB;

use PDO;

class DBManager
{
    protected $db;

    public function __construct(PDO $db) {
        $this->db = $db;
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
