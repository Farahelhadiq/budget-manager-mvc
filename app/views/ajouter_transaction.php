<?php
require_once __DIR__ . '/../controllers/TransactionController.php';
$controller = new TransactionController();
$data = $controller->handleAddTransaction();

$revenuCategories = $data['revenuCategories'];
$depenseCategories = $data['depenseCategories'];
$success = $data['success'];
$error = $data['error'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Transaction</title>
 <link rel="stylesheet" href="../../assets/css/ajouter.css?v=2">
 <script src="https://unpkg.com/feather-icons"></script>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-brand">
            <h1>Budget Manager</h1>
        </div>
        <div class="navbar-links">
            <a href="ajouter_transaction.php" class="active">Ajouter Transaction</a>
            <a href="view_transactions.php">Voir Transactions</a>
            <a href="login.php">se connecter</a>
        </div>
    </nav>

    <main class="main-content">
        <div class="transaction-container">
            <div class="transaction-header">
                <h2>Ajouter une Transaction</h2>
                <p>Enregistrez vos revenus et dépenses avec précision</p>
            </div>
            <form action="" method="POST" class="transaction-form">
    <?php if ($success): ?><p class="success"><?= htmlspecialchars($success) ?></p><?php endif; ?>
    <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>

    <div class="form-group">
        <label for="type_transaction">Type de transaction</label>
        <div class="radio-group">
            <input type="radio" name="type_transaction" id="revenu" value="revenu" onclick="updateCategories()" required>
            <label for="revenu">Revenu</label>
            <input type="radio" name="type_transaction" id="depense" value="depense" onclick="updateCategories()" required>
            <label for="depense">Dépense</label>
        </div>
    </div>

    <div class="form-group">
        <label for="montant">Montant</label>
        <div class="input-icon">
            <i data-feather="dollar-sign"></i>
            <input type="number" name="montant" id="montant" class="form-control" min="0.01" step="0.01" required>
        </div>
    </div>

    <div class="form-group">
        <label for="categorie">Catégorie</label>
        <div class="input-icon">
            <i data-feather="list"></i>
            <select name="categorie" id="categorie" class="form-control" required>
                <option value="" disabled selected>Choisir une catégorie</option>
            </select>
        </div>
    </div>

    <div class="form-group">
        <label for="description">Description (optionnel)</label>
        <div class="input-icon">
            <i data-feather="file-text"></i>
            <input type="text" name="description" id="description" class="form-control">
        </div>
    </div>

    <div class="form-group">
        <label for="date">Date</label>
        <div class="input-icon">
            <i data-feather="calendar"></i>
            <input type="date" name="date" id="date" class="form-control" required>
        </div>
    </div>

    <button type="submit" name="submit" class="btn transaction-btn">Ajouter la transaction</button>

    <div class="actions">
        <a href="view_transactions.php" class="btn-link"><i data-feather="eye"></i>Voir toutes les transactions</a>
    </div>
</form>

        </div>
    </main>

    <footer class="footer">
        <p>© 2025 Budget Manager. Tous droits réservés. <a href="mailto:contact@budgetmanager.com">Nous contacter</a></p>
    </footer>
    <script>
        const revenuCategories = <?php echo json_encode($revenuCategories); ?>;
        const depenseCategories = <?php echo json_encode($depenseCategories); ?>;

        function updateCategories() {
            const type = document.querySelector('input[name="type_transaction"]:checked')?.value;
            const select = document.getElementById('categorie');
            select.innerHTML = '';

            let categories = [];
            if (type === 'revenu') {
                categories = revenuCategories;
            } else if (type === 'depense') {
                categories = depenseCategories;
            }

            categories.forEach(cat => {
                const option = document.createElement('option');
                option.value = cat;
                option.textContent = cat;
                select.appendChild(option);
            });
        }

        window.addEventListener('DOMContentLoaded', updateCategories);
       feather.replace();
    </script>
</body>
</html>