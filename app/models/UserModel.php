<?php
require_once __DIR__ . '/../database/Database.php';

class UserModel {
    private $pdo;

    public function __construct() {
        $this->pdo = Database::getInstance()->getConnection();
    }

    public function isEmailExists($email) {
        $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $count = $stmt->fetchColumn();
        return $count > 0;
    }

    public function register($nom, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (nom, email, password) VALUES (:nom, :email, :password)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'nom' => $nom,
            'email' => $email,
            'password' => $hashedPassword
        ]);
    }

    public function login($email, $password) {
        $sql = "SELECT id, nom, email, password FROM users WHERE email = :email";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user; // Return user data (id, nom, email)
        }
        return false; // Return false if credentials are invalid
    }
}