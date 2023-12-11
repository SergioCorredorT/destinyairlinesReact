<?php
require_once './Database/Database.php';
require_once './Tools/IniTool.php';

abstract class BaseMultiModel
{
    private $con;
    private $iniTool;
    public function __construct()
    {
        $this->iniTool = new IniTool('./Config/cfg.ini');
        $cfgDatabase = $this->iniTool->getKeysAndValues("database");

        $this->con = Database::getInstance($cfgDatabase)->getConnection();
    }

    public function __destruct()
    {
        if ($this->con) {
            $this->con = null;  // Cerrar la conexión si aún está abierta
        }
    }

    protected function executeSql($sql, $params = [])
    {
        //Params contendrá un array asociativo con los nombres de los parámetros contenidos en el sql y su valor
        try {
            // Preparar la sentencia SQL
            $stmt = $this->con->prepare($sql);
    
            // Vincular los parámetros
            foreach ($params as $key => &$val) {
                $stmt->bindParam($key, $val);
            }
    
            // Ejecutar la sentencia
            $stmt->execute();
    
            // Comprobar si la sentencia es un SELECT
            if (stripos(trim($sql), 'SELECT') === 0) {
                // Si es un SELECT, devolver los resultados
                if (intval($stmt->errorCode()) === 0) {
                    return $stmt->fetchAll(PDO::FETCH_ASSOC);
                } else {
                    return false;
                }
            } else {
                // Si no es un SELECT, devolver true si la sentencia se ejecutó correctamente
                return intval($stmt->errorCode()) === 0;
            }
        } catch (Exception $er) {
            error_log('Catched exception: ' .  $er->getMessage() . "\n");
            return false;
        }
    }
}
