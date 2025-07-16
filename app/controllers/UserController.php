<?php
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../database/Database.php';

class UserController {
    private $userModel;

    public function __construct() {
        $this->userModel = new UserModel(Database::getInstance()->getConnection());
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
            $this->userModel->register($nom, $email, $password);
            header('Location: login.php');
            exit();
        }

        return $errors;
    }
}
