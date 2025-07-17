<?php
require_once __DIR__ . '/../controllers/TransactionController.php';
$controller = new TransactionController();
$data = $controller->handleViewTransactions();

$transactions = $data['transactions'];
$solde = $data['solde'];
$resume_mois = $data['resume_mois'];
$categories = $data['categories'];
$max_values = $data['max_values'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Voir les Transactions</title>
    <link rel="stylesheet" href="assets/css/transaction.css">
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
            <a href="login.php">se connecter</a>
        </div>
    </nav>
    <div class="container">
        <div class="header">
            <h1><i class="fas fa-coins"></i> Transactions</h1>
            <a href="ajouter_transaction.php" class="btn">
                <i class="fas fa-plus"></i> Ajouter une transaction
            </a>
        </div>

        <div class="dashboard">
            <h2 class="section-title"><i class="fas fa-chart-line"></i> Informations financières</h2>
            
            <div class="cards">
                <div class="card card-solde">
                    <h3 class="card-title"><i class="fas fa-wallet"></i> Solde actuel</h3>
                    <div class="card-value"><?= number_format($solde, 2, ',', ' ') ?> €</div>
                </div>
                
                <div class="card card-revenus">
                    <h3 class="card-title"><i class="fas fa-arrow-up"></i> Total des revenus</h3>
                    <div class="card-value"><?= number_format($resume_mois['total_revenus'], 2, ',', ' ') ?> €</div>
                </div>
                
                <div class="card card-depenses">
                    <h3 class="card-title"><i class="fas fa-arrow-down"></i> Total des dépenses</h3>
                    <div class="card-value"><?= number_format(abs($resume_mois['total_depenses']), 2, ',', ' ') ?> €</div>
                </div>
                
                <div class="card card-max-depense">
                    <h3 class="card-title"><i class="fas fa-exclamation-triangle"></i> Dépense la plus haute</h3>
                    <div class="card-value"><?= number_format(abs($max_values['max_depense']), 2, ',', ' ') ?> €</div>
                </div>
                
                <div class="card card-max-revenu">
                    <h3 class="card-title"><i class="fas fa-trophy"></i> Revenu le plus élevé</h3>
                    <div class="card-value"><?= number_format($max_values['max_revenu'], 2, ',', ' ') ?> €</div>
                </div>
            </div>

            <h2 class="section-title"><i class="fas fa-tags"></i> Somme des revenus et dépenses par catégorie</h2>
            
            <div class="table-wrapper">
                <table class="categories">
                    <thead>
                        <tr>
                            <th><i class="fas fa-folder"></i> Catégorie</th>
                            <th><i class="fas fa-plus-circle"></i> Total des Revenus</th>
                            <th><i class="fas fa-minus-circle"></i> Total des Dépenses</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $categorie): ?>
                            <tr>
                                <td><?= htmlspecialchars($categorie['nom']) ?></td>
                                <td class="amount-positive"><?= number_format($categorie['total_revenus'], 2, ',', ' ') ?> €</td>
                                <td class="amount-negative"><?= number_format(abs($categorie['total_depenses']), 2, ',', ' ') ?> €</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <h2 class="section-title"><i class="fas fa-list"></i> Liste des transactions</h2>
        
        <?php if (empty($transactions)): ?>
            <div class="empty-state">
                <i class="fas fa-search"></i>
                <p>Aucune transaction trouvée.</p>
            </div>
        <?php else: ?>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Montant</th>
                            <th>Catégorie</th>
                            <th>Description</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $transaction): ?>
                            <tr>
                                <td><?= htmlspecialchars($transaction['id']) ?></td>
                                <td class="<?= $transaction['montant'] > 0 ? 'amount-positive' : 'amount-negative' ?>">
                                    <?= number_format($transaction['montant'], 2, ',', ' ') ?> €
                                </td>
                                <td>
                                    <i class="fas fa-tag"></i> <?= htmlspecialchars($transaction['categorie']) ?>
                                </td>
                                <td><?= htmlspecialchars($transaction['description']) ?></td>
                                <td>
                                    <i class="far fa-calendar-alt"></i> <?= date('d/m/Y', strtotime($transaction['date_transaction'])) ?>
                                </td>
                                <td>
                                    <a href="modifier_transaction.php?id=<?= $transaction['id'] ?>" class="action-btn edit-btn" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="delete_id" value="<?= $transaction['id'] ?>">
                                        <button type="submit" class="action-btn delete-btn" title="Supprimer" onclick="return confirm('Supprimer cette transaction ?')">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    <footer class="footer">
        <p>© 2025 Budget Manager. Tous droits réservés. <a href="mailto:contact@budgetmanager.com">Nous contacter</a></p>
    </footer>
</body>
</html>
