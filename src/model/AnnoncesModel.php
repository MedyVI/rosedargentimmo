<?php

require_once __DIR__ . '/../config/database.php'; // Inclusion de la connexion à la base de données

class AnnoncesModel {
    private $pdo;

    public function __construct($pdo) {
        if (!$pdo) {
            throw new Exception("Erreur : La connexion à la base de données est absente !");
        }        
        $this->pdo = $pdo;
    }

    public function getAnnonces() {
        try {
            $stmt = $this->pdo->query("SELECT * FROM annonces ORDER BY date_publication DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des annonces : " . $e->getMessage());
        }
    }
}
?>
