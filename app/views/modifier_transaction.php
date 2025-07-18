<?php
session_start();
require_once __DIR__ . '/../controllers/TransactionController.php';

// Vérification de la session
if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header('Location: view_transactions.php');
    exit;
}

$controller = new TransactionController();
$data = $controller->handleModifierTransaction($_GET['id'], $_SESSION['user_id']);

$transaction = $data['transaction'];
$categories = $data['categories'];
$errors = $data['errors'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier la Transaction</title>
    <link rel="stylesheet" href="assets/css/modifier.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<nav class="navbar">
    <div class="navbar-brand">
        <h1>Budget Manager</h1>
    </div>
    <div class="navbar-links">
        <a href="ajouter_transaction.php" class="active">Ajouter Transaction</a>
        <a href="view_transactions.php">Voir Transactions</a>
        <a href="login.php">Se connecter</a>
    </div>
</nav>

<div class="container">
    <div class="header">
        <h1><i class="fas fa-edit"></i> Modifier la Transaction</h1>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error) ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="card">
        <form method="post">
            <div class="form-group">
                <label for="montant"><i class="fas fa-euro-sign"></i> Montant:</label>
                <div class="input-icon">
                    <i class="fas fa-euro-sign"></i>
                    <input type="number" step="0.01" id="montant" name="montant"
                           value="<?= htmlspecialchars($transaction['montant']) ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="description"><i class="fas fa-align-left"></i> Description:</label>
                <div class="input-icon">
                    <i class="fas fa-pen"></i>
                    <input type="text" id="description" name="description"
                           value="<?= htmlspecialchars($transaction['description']) ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="date_transaction"><i class="fas fa-calendar-alt"></i> Date:</label>
                <div class="input-icon">
                    <i class="fas fa-calendar"></i>
                    <input type="date" id="date_transaction" name="date_transaction"
                           value="<?= htmlspecialchars($transaction['date_transaction']) ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="category_id"><i class="fas fa-tag"></i> Catégorie:</label>
                <div class="input-icon">
                    <i class="fas fa-folder"></i>
                    <select id="category_id" name="category_id" required>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $transaction['category_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="actions">
                <a href="view_transactions.php" class="btn-link">
                    <i class="fas fa-arrow-left"></i> Retour aux transactions
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Mettre à jour
                </button>
            </div>
        </form>
    </div>
</div>

<footer class="footer">
    <p>© 2025 Budget Manager. Tous droits réservés.</p>
</footer>
</body>
</html>
