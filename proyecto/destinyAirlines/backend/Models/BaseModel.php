<?php
require_once '../Database/Database.php';
require_once '../Config/IniController.php';

class BaseModel
{
    private $con;
    private $tableName;

    protected function __construct($tableName)
    {
        $iniController = new IniController('../Config/cfg.ini');
        $config = $iniController->getKeysAndValues("database");

        $this->con = Database::getInstance($config)->getConnection();
        $this->tableName = $tableName;
    }

    protected function insert($data)
    {
        $columns = implode(', ', array_keys($data));
        $values = "'" . implode("', '", array_values($data)) . "'";
        $query = "INSERT INTO $this->tableName ($columns) VALUES ($values)";

        // Execute the query and return the result
        return $this->con->exec($query);
    }

    protected function select($columns = '*', $where = '')
    {
        $query = "SELECT $columns FROM $this->tableName";
        if ($where) {
            $query .= " WHERE $where";
        }

        // Execute the query and return results
        $stmt = $this->con->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function update($data, $where)
    {
        $updateData = '';
        foreach ($data as $key => $value) {
            $updateData .= "$key = '$value', ";
        }
        $updateData = rtrim($updateData, ', ');

        $query = "UPDATE $this->tableName SET $updateData WHERE $where";

        // Execute the query and return the result
        return $this->con->exec($query);
    }

    protected function delete($where)
    {
        $query = "DELETE FROM $this->tableName WHERE $where";

        // Execute the query and return the result
        return $this->con->exec($query);
    }

    protected function beginTransaction()
    {
        return $this->con->beginTransaction();
    }

    protected function commit()
    {
        return $this->con->commit();
    }

    protected function rollBack()
    {
        return $this->con->rollBack();
    }
}
