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
}
