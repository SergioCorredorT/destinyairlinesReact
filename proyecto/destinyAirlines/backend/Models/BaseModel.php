<?php
require_once './Database/Database.php';
require_once './Tools/IniTool.php';

abstract class BaseModel
{
    private $con;
    private $tableName;
    private $iniTool;

    protected function __construct($tableName)
    {
        $this->iniTool = new IniTool('./Config/cfg.ini');
        $config = $this->iniTool->getKeysAndValues("database");

        $this->con = Database::getInstance($config)->getConnection();
        $this->tableName = $tableName;
    }

    public function __destruct()
    {
        if ($this->con) {
            $this->con = null;  // Cerrar la conexión si aún está abierta
        }
    }

    protected function insert($datas)
    {
        //Ejemplos:
        /*
            $datas = [
                ['firstName' => 'AAAA', 'zipCode' => 25, 'emailAddress' => 'aaaa@example.com', 'password' => 'contraseña1'],
                ['firstName' => 'BBBB', 'zipCode' => 30, 'emailAddress' => 'bbbb@example.com', 'password' => 'contraseña2']
            ];
            if($usuario->insert($datas)){echo "bieeeeeeeeeeen";}else{echo "maaaaaaaaaaaal";};
        */
        /*
            $datas = [
                'firstName' => 'AAAA', 'zipCode' => 25, 'emailAddress' => 'aaaa@example.com', 'password' => 'contraseña1'
            ];
            if($usuario->insert($datas)){echo "bieeeeeeeeeeen";}else{echo "maaaaaaaaaaaal";};
        */
        if (array_keys($datas) !== range(0, count($datas) - 1)) {
            $datas = [$datas];
        }

        $this->con->beginTransaction();

        try {
            foreach ($datas as $data) {
                if (!$this->insertOne($data)) {
                    $this->con->rollBack();
                    return false;
                }
            }

            $this->con->commit();
            return true;
        } catch (PDOException $e) {
            $this->con->rollBack();
            echo 'Se ha capturado una excepción: ' . $e->getMessage() . "\n";
            return false;
        } finally {
            $this->con = null;  // Cerrar la conexión
        }
    }

    private function insertOne($data)
    {
        $data = $this->cleanAll($data);
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $query = "INSERT INTO $this->tableName ($columns) VALUES ($placeholders)";
        try {
            $stmt = $this->con->prepare($query);
        } catch (Exception $er) {
            echo 'Se ha capturado una excepción: ',  $er->getMessage(), "\n";
            return false;
        }
        // Bind parameters
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        $stmt->execute();
        if (intval($stmt->errorCode()) === 0) {
            return true;
        } else {
            return false;
        }
    }

    protected function select($columns = '*', $where = '')
    {
        //Ejemplo:
        //print_r($usuario->select());
        $query = "SELECT $columns FROM $this->tableName";
        if ($where) {
            $query .= " WHERE $where";
        }

        // Prepare and execute the query
        try {
            $stmt = $this->con->prepare($query);
        } catch (Exception $er) {
            echo 'Se ha capturado una excepción: ',  $er->getMessage(), "\n";
            return false;
        }
        $stmt->execute();
        if (intval($stmt->errorCode()) === 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    protected function update($data, $where)
    {
        //Ejemplo:
        /*
            $datas = ["zipCode"=>"11112", "phoneNumber3"=>"123456"];
            if($usuario->update($datas, "country LIKE 'USA'")){echo "bieeeeeeeeeeen";}else{echo "maaaaaaaaaaaal";};
        */
        $updateData = '';
        foreach ($data as $key => $value) {
            $updateData .= "$key = :$key, ";
        }
        $updateData = rtrim($updateData, ', ');

        $query = "UPDATE $this->tableName SET $updateData WHERE $where";

        // Prepare the query
        try {
            $stmt = $this->con->prepare($query);
        } catch (Exception $er) {
            echo 'Se ha capturado una excepción: ',  $er->getMessage(), "\n";
            return false;
        }
        // Bind parameters and execute the query
        foreach ($data as $key => &$value) {
            $stmt->bindParam(':' . $key, $value);
        }

        $stmt->execute();
        if (intval($stmt->errorCode()) === 0) {
            return true;
        } else {
            return false;
        }
    }

    protected function delete($where)
    {
        //Ejemplo:
        //if($usuario->delete("firstName LIKE Emily")){echo "bieeeeeeeeeeen";}else{echo "maaaaaaaaaaaal";};
        [$column, $operator, $value] = explode(" ", $where);

        $query = "DELETE FROM $this->tableName WHERE $column $operator :value";

        try {
            $stmt = $this->con->prepare($query);

            if ($operator == 'LIKE') {
                $value = "%$value%";
            }
            $stmt->bindValue(':value', trim($value));
        } catch (Exception $er) {
            echo 'Se ha capturado una excepción: ',  $er->getMessage(), "\n";
            return false;
        }

        $stmt->execute();
        if (intval($stmt->errorCode()) === 0) {
            return true;
        } else {
            return false;
        }
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

    protected function cleanAll($data)
    {
        if (is_array($data)) {
            $cleanedData = [];
            foreach ($data as $key => $value) {
                $cleanKey = $this->cleanString((string)$key);
                $cleanedData[$cleanKey] = $this->cleanString($value);
            }
            return $cleanedData;
        } else {
            return $this->cleanString($data);
        }
    }

    protected function cleanString($myString)
    {
        $restrictedWords = ["<script>", "</script>", "<script src", "<script type=", "SELECT * FROM", "SELECT ", " SELECT ", "DELETE FROM", "INSERT INTO", "DROP TABLE", "DROP DATABASE", "TRUNCATE TABLE", "SHOW TABLES", "SHOW DATABASES", "<?php", "?>", "--", "^", "<", ">", "==", "=", ";", "::"];

        $myString = trim($myString);
        $myString = stripslashes($myString);

        foreach ($restrictedWords as $restrictedWord) {
            $myString = str_ireplace($restrictedWord, "", $myString);
        }

        $myString = trim($myString);
        $myString = stripslashes($myString);

        return $myString;
    }
}
