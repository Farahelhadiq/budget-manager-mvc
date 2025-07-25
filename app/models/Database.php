<?php
class Database {
    private $host = 'localhost';
    private $dbname = 'gestion_budget';
    private $username = 'root';
    private $password = '';
    private $pdo;
    private static $instance = null;

    private function __construct() {
        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4",
                $this->username,
                $this->password
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    // Singleton pattern باش تكون instance وحدة ديما
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    // باش تستعمل الـ PDO من الخارج
    public function getConnection() {
        return $this->pdo;
    }
}
