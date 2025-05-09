<?php

class Database {
    private static $instance = null;
    private $host = 'localhost';
    private $db_name = 'cms';
    private $username = 'root';
    private $password = '';
    private $conn;

    private function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            die("Erro de conexão: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}

// Criar uma instância da classe Database
$database = Database::getInstance();
$connection = $database->getConnection();

/*if($connection){
    echo "Conectado!!";
}*/

?>