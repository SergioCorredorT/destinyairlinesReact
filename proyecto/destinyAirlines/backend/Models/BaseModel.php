<?php

class BaseModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function select($tableName, $columns = '*', $where = '') {
        $query = "SELECT $columns FROM $tableName";
        if ($where) {
            $query .= " WHERE $where";
        }

        // Execute the query and return results
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function insert($tableName, $data) {
        $columns = implode(', ', array_keys($data));
        $values = "'" . implode("', '", array_values($data)) . "'";
        $query = "INSERT INTO $tableName ($columns) VALUES ($values)";

        // Execute the query and return the result
        return $this->db->exec($query);
    }

    public function update($tableName, $data, $where) {
        $updateData = '';
        foreach ($data as $key => $value) {
            $updateData .= "$key = '$value', ";
        }
        $updateData = rtrim($updateData, ', ');

        $query = "UPDATE $tableName SET $updateData WHERE $where";

        // Execute the query and return the result
        return $this->db->exec($query);
    }

    public function delete($tableName, $where) {
        $query = "DELETE FROM $tableName WHERE $where";

        // Execute the query and return the result
        return $this->db->exec($query);
    }
}

?>
