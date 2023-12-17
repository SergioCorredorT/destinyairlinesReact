<?php
final class Database
{
    private static $instance = null;
    private $conn = null;

    private function __construct(array $config)
    {
        try {
            $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};port={$config['port']}";
            $options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                //PDO::ATTR_PERSISTENT => true,  // Use a persistent connection
                PDO::ATTR_EMULATE_PREPARES => false,  // Disable emulated prepared statements
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,  // Establecer modo de recuperación predeterminado
            );

            $this->conn = new PDO($dsn, $config['username'], $config['password'], $options);
            $this->conn->exec("SET CHARACTER SET utf8");
        } catch (PDOException $e) {
            throw new Exception("Failed to connect to the database: " . $e->getMessage());
        }
    }

    public static function getInstance(array $config): self
    {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    public function getConnection(): PDO|null
    {
        return $this->conn;
    }

    public function destroyConnection(): bool
    {
        $this->conn = null;
        self::$instance = null;
        return true;
    }
}
?>
