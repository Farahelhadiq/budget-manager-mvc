<?php
require_once __DIR__ . '/../models/TransactionModel.php';
require_once __DIR__ . '/../database/Database.php';

class TransactionController {
    private $transactionModel;

    public function __construct() {
        $this->transactionModel = new TransactionModel(Database::getInstance()->getConnection());
    }

    public function handleViewTransactions() {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: login.php');
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $mois_en_cours = date('Y-m');

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
            $this->transactionModel->deleteTransaction($_POST['delete_id'], $user_id);
            header('Location: view_transactions.php');
            exit;
        }

        return [
            'transactions' => $this->transactionModel->getTransactionsByUser($user_id),
            'solde' => $this->transactionModel->getSolde($user_id),
            'resume_mois' => $this->transactionModel->getResumeMois($user_id, $mois_en_cours),
            'categories' => $this->transactionModel->getCategoriesSummary($user_id),
            'max_values' => $this->transactionModel->getMaxValues($user_id, $mois_en_cours),
        ];
    }

    // ✅ Nouvelle méthode pour modifier une transaction
    public function handleModifierTransaction($transaction_id, $user_id) {
        $transaction = $this->transactionModel->getTransactionById($transaction_id, $user_id);

        if (!$transaction) {
            header('Location: view_transactions.php');
            exit();
        }

        $categories = $this->transactionModel->getCategories();
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $montant = $_POST['montant'];
            $description = $_POST['description'];
            $date_transaction = $_POST['date_transaction'];
            $category_id = $_POST['category_id'];

            if ($this->transactionModel->updateTransaction($transaction_id, $user_id, $montant, $description, $date_transaction, $category_id)) {
                header('Location: view_transactions.php');
                exit();
            } else {
                $errors[] = "Erreur lors de la mise à jour.";
            }
        }

        return [
            'transaction' => $transaction,
            'categories' => $categories,
            'errors' => $errors
        ];
    }
}
