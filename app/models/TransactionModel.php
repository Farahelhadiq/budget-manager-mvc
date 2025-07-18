<?php
require_once __DIR__ . '/../database/Database.php';

class TransactionModel {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function getTransactionsByUser($user_id) {
        $stmt = $this->pdo->prepare("SELECT t.id, t.montant, t.description, t.date_transaction, c.nom AS categorie
                                    FROM transactions t
                                    JOIN categories c ON t.category_id = c.id
                                    WHERE t.user_id = ?
                                    ORDER BY t.date_transaction DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getSolde($user_id) {
        $stmt = $this->pdo->prepare("SELECT SUM(montant) AS total FROM transactions WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getResumeMois($user_id, $mois_en_cours) {
        $stmt = $this->pdo->prepare("SELECT 
                SUM(CASE WHEN montant > 0 THEN montant ELSE 0 END) AS total_revenus, 
                SUM(CASE WHEN montant < 0 THEN montant ELSE 0 END) AS total_depenses
            FROM transactions
            WHERE user_id = ? AND DATE_FORMAT(date_transaction, '%Y-%m') = ?");
        $stmt->execute([$user_id, $mois_en_cours]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCategoriesSummary($user_id) {
        $stmt = $this->pdo->prepare("SELECT c.nom, 
                SUM(CASE WHEN t.montant > 0 THEN t.montant ELSE 0 END) AS total_revenus,
                SUM(CASE WHEN t.montant < 0 THEN t.montant ELSE 0 END) AS total_depenses
            FROM transactions t
            JOIN categories c ON t.category_id = c.id
            WHERE t.user_id = ?
            GROUP BY c.nom");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMaxValues($user_id, $mois_en_cours) {
        $stmt = $this->pdo->prepare("SELECT 
                MAX(montant) AS max_revenu, 
                MIN(montant) AS max_depense
            FROM transactions
            WHERE user_id = ? AND DATE_FORMAT(date_transaction, '%Y-%m') = ?");
        $stmt->execute([$user_id, $mois_en_cours]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deleteTransaction($transaction_id, $user_id) {
        $stmt = $this->pdo->prepare("DELETE FROM transactions WHERE id = ? AND user_id = ?");
        return $stmt->execute([$transaction_id, $user_id]);
    }
    public function getTransactionById($transaction_id, $user_id) {
    $stmt = $this->pdo->prepare("SELECT * FROM transactions WHERE id = ? AND user_id = ?");
    $stmt->execute([$transaction_id, $user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function getCategories() {
    $stmt = $this->pdo->prepare("SELECT * FROM categories");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function updateTransaction($transaction_id, $user_id, $montant, $description, $date_transaction, $category_id) {
    $stmt = $this->pdo->prepare("UPDATE transactions 
        SET montant = ?, description = ?, date_transaction = ?, category_id = ? 
        WHERE id = ? AND user_id = ?");
    return $stmt->execute([$montant, $description, $date_transaction, $category_id, $transaction_id, $user_id]);
}
public function getCategoriesByType($type) {
    $stmt = $this->pdo->prepare("SELECT nom FROM categories WHERE type = ?");
    $stmt->execute([$type]);
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

public function getCategoryId($category_name, $type) {
    $stmt = $this->pdo->prepare("SELECT id FROM categories WHERE nom = ? AND type = ?");
    $stmt->execute([$category_name, $type]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row ? $row['id'] : null;
}

public function addTransaction($user_id, $category_id, $montant, $description, $date) {
    $stmt = $this->pdo->prepare("INSERT INTO transactions (user_id, category_id, montant, description, date_transaction)
                                VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$user_id, $category_id, $montant, $description, $date]);
}

}
