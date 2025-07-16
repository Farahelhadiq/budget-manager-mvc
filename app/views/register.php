<?php
require_once __DIR__ . '/../../app/controllers/UserController.php';

$errorMsg = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new UserController();
    $errorMsg = $controller->registerUser($_POST['nom'], $_POST['email'], $_POST['password'], $_POST['confirmPassword']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="../assets/css/register.css?v=1">
</head>
<body>
    <div class="register-container">
        <div class="register-header">
            <h2>Inscription</h2>
            <p>Rejoignez-nous pour optimiser votre budget</p>
        </div>
        <form method="POST" class="register-form">
            <div class="form-group">
                <input type="text" name="nom" placeholder="Nom">
                <span class="error"><?php echo $errorMsg['inputVide'] ?? ''; ?></span>
            </div>
            <div class="form-group">
                <input type="email" name="email" placeholder="Email">
                <span class="error"><?php echo $errorMsg['email'] ?? ''; ?></span>
                <span class="error"><?php echo $errorMsg['emailexiste'] ?? ''; ?></span>
            </div>
            <div class="form-group">
                <input type="password" name="password" placeholder="Mot de passe">
                <span class="error"><?php echo $errorMsg['password'] ?? ''; ?></span>
            </div>
            <div class="form-group">
                <input type="password" name="confirmPassword" placeholder="Confirmer le mot de passe">
            </div>
            <button type="submit">S'inscrire</button>
        </form>
    </div>
</body>
</html>
