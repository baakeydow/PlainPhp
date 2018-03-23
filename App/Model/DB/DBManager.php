<?php
namespace Model\DB;

use PDO;
use PDOException;

class DBManager
{
    protected $db;

    public function __construct(PDO $db) {
        $this->db = $db;
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
