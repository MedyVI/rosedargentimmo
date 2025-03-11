<?php

require_once __DIR__ . '/../config/database.php'; // Inclusion de la connexion à la base de données

class AnnoncesModel {
    private $pdo;

    /**
     * Constructeur de la classe AnnoncesModel.
     * @param PDO $pdo Instance PDO pour la connexion à la base de données.
     * @throws Exception Si la connexion à la base de données est absente.
     */
    public function __construct($pdo) {
        if (!$pdo) {
            throw new Exception("Erreur : La connexion à la base de données est absente !");
        }        
        $this->pdo = $pdo;
    }

    /**
     * Récupère toutes les annonces avec leurs images associées.
     * @return array Liste des annonces avec leurs images.
     * @throws Exception En cas d'erreur SQL.
     */
    public function getAnnoncesWithImages() {
        try {
            $stmt = $this->pdo->query("
                SELECT 
                    a.id, a.reference, a.titre, a.description, a.prix, a.type, 
                    a.surface, a.localisation, a.code_postal, a.ville, 
                    a.statut, a.date_publication, 
                    (SELECT i.url FROM images i WHERE i.annonce_id = a.id LIMIT 1) AS image_url
                FROM annonces a
                ORDER BY a.date_publication DESC
            ");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des annonces avec images : " . $e->getMessage());
        }
    }
    

    /**
     * Récupère une annonce spécifique par son ID.
     * @param int $id Identifiant de l'annonce.
     * @return array|null Données de l'annonce ou null si non trouvée.
     * @throws Exception En cas d'erreur SQL.
     */
    public function getAnnonceById($id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    a.*, 
                    GROUP_CONCAT(i.url SEPARATOR ',') AS images
                FROM annonces a
                LEFT JOIN images i ON a.id = i.annonce_id
                WHERE a.id = :id
                GROUP BY a.id
            ");
            $stmt->execute(['id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération de l'annonce : " . $e->getMessage());
        }
    }
}
?>
