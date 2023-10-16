<?php
final class Database
{
  private static $instance = null;
  private $conn;

  private function __construct($config)
  {
    try {
      $this->conn = new PDO("mysql:host=" . $config['host'] . ";dbname=" . $config['dbname'] . ";port=" . $config['port'], $config['username'], $config['password']);
      // set the PDO error mode to exception
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $this->conn->exec("SET CHARACTER SET utf8");
      echo "Succesfull database connection";
    } catch (PDOException $e) {
      echo "Fail database connection: " . $e->getMessage();
    }
  }

  public static function getInstance($config)
  {
    if (self::$instance == null) {
      self::$instance = new Database($config);
    }
    return self::$instance;
  }

  public function getConnection()
  {
    return $this->conn;
  }

  public function destroyConnection()
  {
    if ($this->conn !== null) {
      $this->conn = null;
    }
  }
}
