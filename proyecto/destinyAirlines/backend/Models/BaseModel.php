<?php
require_once './Database/Database.php';
require_once './Tools/IniTool.php';

abstract class BaseModel
{
    private $con;
    private $tableName;
    private $iniTool;

    protected function __construct(string $tableName)
    {
        $this->iniTool = new IniTool('./Config/cfg.ini');
        $cfgDatabase = $this->iniTool->getKeysAndValues("database");

        $this->con = Database::getInstance($cfgDatabase)->getConnection();
        $this->tableName = $tableName;
    }

    public function __destruct()
    {
        if ($this->con) {
            $this->con = null;  // Cerrar la conexión si aún está abierta
        }
    }

    //Hace insert un INSERT para todos los registros pudiendo devolver la id generada del último registro insertado
    protected function insertMultiple(array $datas, bool $getId = false)
    {
        if (array_keys($datas) !== range(0, count($datas) - 1)) {
            $datas = [$datas];
        }

        $columns = implode(', ', array_keys($datas[0]));
        $placeholders = rtrim(str_repeat('(' . implode(', ', array_fill(0, count($datas[0]), '?')) . '), ', count($datas)), ', ');
        $query = "INSERT INTO $this->tableName ($columns) VALUES $placeholders";

        $values = [];
        foreach ($datas as $data) {
            $values = array_merge($values, array_values($data));
        }

        try {
            $stmt = $this->con->prepare($query);
            $stmt->execute($values);
            if (intval($stmt->errorCode()) === 0) {
                return $getId ? $this->con->lastInsertId() : true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            error_log('Catched exception: ' . $e->getMessage() . "\n");
            return false;
        }
    }

    //Hace insert un INSERT por cada registro pudiendo devolver un array con las id generadas
    protected function insert(array $datas, bool $getId = false)
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

        //$this->con->beginTransaction();
        $insertedIds = [];

        try {
            foreach ($datas as $data) {
                $insertResult = $this->insertOne($data, $getId);

                if (!$insertResult) {
                    $this->con->rollBack();
                    return false;
                }

                if ($getId) {
                    $insertedIds[] = $insertResult;
                }
            }

            //$this->con->commit();
            return $getId ? $insertedIds : true;
        } catch (PDOException $e) {
            //$this->con->rollBack();
            error_log('Catched exception: ' . $e->getMessage() . "\n");
            return false;
        }
    }

    private function insertOne(array $data, bool $getId = false)
    {
        $data = $this->sanitizeAll($data);
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        $query = "INSERT INTO $this->tableName ($columns) VALUES ($placeholders)";
        try {
            $stmt = $this->con->prepare($query);
        } catch (Exception $er) {
            error_log('Catched exception: ' .  $er->getMessage() . "\n");
            return false;
        }
        // Bind parameters
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }

        $stmt->execute();
        if (intval($stmt->errorCode()) === 0) {
            return $getId ? $this->con->lastInsertId() : true;
        } else {
            return false;
        }
    }

    protected function select(string $columns = '*', string $where = '')
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
            error_log('Catched exception: ' .  $er->getMessage() . "\n");
            return false;
        }
        $stmt->execute();
        if (intval($stmt->errorCode()) === 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return false;
        }
    }

    public function addQuotesIfNecessary($miString)
    {
        if (is_string($miString)) {
            $miString = trim($miString);
            $firstChar = $miString[0];
            $lastChar = $miString[strlen($miString) - 1];

            if (($firstChar != '"' && $lastChar != '"') && ($firstChar != "'" && $lastChar != "'")) {
                $miString = "'" . $miString . "'";
            }
        }

        return $miString;
    }

    //Esta versión previene mejor el injection
    protected function update(array $data, string $where)
    {
        $updateData = '';
        $bindValues = [];
        $i = 1;
        foreach ($data as $key => $value) {
            if (preg_match('/[\\+\\-\\*\\/]/', $value)) {
                // Handle mathematical expressions specially
                $updateData .= "$key = $value, ";
            } else {
                $updateData .= "$key = :value$i, ";
                $bindValues[":value$i"] = $value;
                $i++;
            }
        }
        $updateData = rtrim($updateData, ', ');
        $query = "UPDATE $this->tableName SET $updateData WHERE $where";

        // Prepare the query
        try {
            $stmt = $this->con->prepare($query);
            foreach ($bindValues as $param => $value) {
                $stmt->bindValue($param, $value);
            }
        } catch (Exception $er) {
            error_log('Catched exception: ' . $er->getMessage() . "\n");
            return false;
        }

        $stmt->execute();
        if (intval($stmt->errorCode()) === 0) {
            return true;
        } else {
            return false;
        }
    }

    protected function delete(string $where)
    {
        // Ejecuta DELETE sin filtro WHERE
        $query = "DELETE FROM $this->tableName WHERE $where";

        try {
            $stmt = $this->con->prepare($query);
            $stmt->execute();

            if (intval($stmt->errorCode()) === 0) {
                return true; // La eliminación fue exitosa
            } else {
                return false; // Error al ejecutar la consulta
            }
        } catch (Exception $er) {
            // Manejo de excepciones en caso de error
            // echo 'Se ha capturado una excepción: ',  $er->getMessage(), "\n";
            return false;
        }
    }

    public function selectAllowedValues($columnName)
    {
        $query = "SELECT COLUMN_TYPE 
                  FROM INFORMATION_SCHEMA.COLUMNS 
                  WHERE TABLE_NAME = '$this->tableName' 
                  AND COLUMN_NAME = '$columnName'";

        // Prepare and execute the query
        try {
            $stmt = $this->con->prepare($query);
        } catch (Exception $er) {
            error_log('Se ha capturado una excepción: ' .  $er->getMessage() . "\n");
            return false;
        }
        $stmt->execute();
        if (intval($stmt->errorCode()) === 0) {
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return explode(",", str_replace("'", "", substr($result[0]['COLUMN_TYPE'], 5, (strlen($result[0]['COLUMN_TYPE']) - 6))));
        } else {
            return false;
        }
    }

    public function beginTransaction()
    {
        return $this->con->beginTransaction();
    }

    public function commit()
    {
        return $this->con->commit();
    }

    public function rollBack()
    {
        return $this->con->rollBack();
    }

    private function sanitizeAll(array|string $data)
    {
        if (is_array($data)) {
            $cleanedData = [];
            foreach ($data as $key => $value) {
                $cleanKey = $this->sanitizeString((string)$key);
                $cleanedData[$cleanKey] = $this->sanitizeString($value);
            }
            return $cleanedData;
        } else {
            return $this->sanitizeString($data);
        }
    }

    private function sanitizeString(string $myString)
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
