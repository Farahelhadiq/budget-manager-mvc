<?php
class CategoryModel {
    private $db;

    public function __construct($pdo) {
        $this->db = $pdo;
    }

    public function getCategoriesByType($type) {
        $stmt = $this->db->prepare("SELECT id, nom FROM categories WHERE type = :type");
        $stmt->execute([':type' => $type]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllCategories() {
        $stmt = $this->db->query("SELECT * FROM categories");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
