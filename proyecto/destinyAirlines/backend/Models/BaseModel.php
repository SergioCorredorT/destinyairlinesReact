<?php
require_once './Database/Database.php';
require_once './Config/IniController.php';

abstract class BaseModel
{
    private $con;
    private $tableName;
    private $iniController;

    protected function __construct($tableName)
    {
        $this->iniController = new IniController('./Config/cfg.ini');
        $config = $this->iniController->getKeysAndValues("database");

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

    protected function cleanData($myString){

        $restrictedWords=["<script>","</script>","<script src","<script type=","SELECT * FROM","SELECT "," SELECT ","DELETE FROM","INSERT INTO","DROP TABLE","DROP DATABASE","TRUNCATE TABLE","SHOW TABLES","SHOW DATABASES","<?php","?>","--","^","<",">","==","=",";","::"];

        $myString=trim($myString);
        $myString=stripslashes($myString);

        foreach($restrictedWords as $restrictedWord){
            $myString=str_ireplace($restrictedWord, "", $myString);
        }

        $myString=trim($myString);
        $myString=stripslashes($myString);

        return $myString;
    }
}
