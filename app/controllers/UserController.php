<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../database/Database.php';
require_once __DIR__ . '/../models/TransactionModel.php';

class UserController {
    private $userModel;
    private $transactionModel;

    public function __construct() {
        $this->userModel = new UserModel(); // Initialize UserModel
        $this->transactionModel = new TransactionModel(Database::getInstance()->getConnection());
    }

    public function registerUser($nom, $email, $password, $confirmPassword) {
        $errors = [];

        if (empty($nom) || empty($email) || empty($password) || empty($confirmPassword)) {
            $errors['inputVide'] = "Tous les champs sont obligatoires.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Adresse e-mail non valide.";
        } elseif ($password !== $confirmPassword) {
            $errors['password'] = "Les mots de passe ne correspondent pas.";
        } elseif ($this->userModel->isEmailExists($email)) {
            $errors['emailexiste'] = "Cet email est déjà utilisé.";
        }

        if (empty($errors)) {
            try {
                $this->userModel->register($nom, $email, $password);
                header('Location: login.php');
                exit();
            } catch (Exception $e) {
                $errors['general'] = "Erreur lors de l'inscription : " . $e->getMessage();
            }
        }

        return $errors;
    }

    public function loginUser($email, $password) {
        $errors = [];

        if (empty($email)) {
            $errors['email'] = "L'email est obligatoire.";
        }
        if (empty($password)) {
            $errors['password'] = "Le mot de passe est obligatoire.";
        }
        if (!empty($errors)) {
            return $errors;
        }

        $user = $this->userModel->login($email, $password);
        if (!$user) {
            $errors['login'] = "Email ou mot de passe incorrect.";
            return $errors;
        }

        // Session start et stockage infos user
        session_start();
        $_SESSION['user_email'] = $email;
        $_SESSION['is_logged_in'] = true;
        $_SESSION['user_id'] = $user['id'];

        // Redirection après connexion réussie
        header('Location: view_transactions.php');
        exit();
    }
}