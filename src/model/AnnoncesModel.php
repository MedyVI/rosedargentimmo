<?php

require_once __DIR__ . '/../config/database.php'; // Inclusion de la connexion à la base de données

class AnnoncesModel
{
    private $pdo;

    /**
     * Constructeur de la classe AnnoncesModel.
     * @param PDO $pdo Instance PDO pour la connexion à la base de données.
     * @throws Exception Si la connexion à la base de données est absente.
     */
    public function __construct($pdo)
    {
        if (!$pdo) {
            throw new Exception("Erreur : La connexion à la base de données est absente !");
        }
        $this->pdo = $pdo;
    }

    /**
     * Récupère les annonces paginées avec leurs images associées.
     * @param int $page Numéro de la page actuelle.
     * @param int $annoncesParPage Nombre d'annonces par page.
     * @return array Liste des annonces paginées avec leurs images.
     * @throws Exception En cas d'erreur SQL.
     */
    public function getAnnoncesPaginees($page, $annoncesParPage = 10)
    {
        try {
            // S'assurer que les valeurs sont positives et bien des entiers
            $page = max(1, (int) $page);
            $annoncesParPage = max(1, (int) $annoncesParPage);
            $offset = ($page - 1) * $annoncesParPage;
    
            $stmt = $this->pdo->prepare("
                SELECT 
                    a.id, a.reference, a.titre, a.description, a.prix, a.type, 
                    a.surface, a.localisation, a.code_postal, a.ville, 
                    a.statut, a.date_publication, 
                    (SELECT i.url FROM images i WHERE i.annonce_id = a.id LIMIT 1) AS image_url
                FROM annonces a
                ORDER BY a.date_publication DESC
                LIMIT :limit OFFSET :offset
            ");
            $stmt->bindValue(':limit', $annoncesParPage, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            
            // Vérification des valeurs avant exécution
            // var_dump("Page: $page", "OFFSET: $offset", "LIMIT: $annoncesParPage");
            // die();
            
            $stmt->execute();
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la récupération des annonces paginées : " . $e->getMessage());
        }
    }
    


    /**
     * Récupère le nombre total d'annonces.
     * @return int Nombre total d'annonces dans la base de données.
     * @throws Exception En cas d'erreur SQL.
     */
    public function countAnnonces()
    {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) AS total FROM annonces");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $result['total'];
        } catch (PDOException $e) {
            throw new Exception("Erreur lors du comptage des annonces : " . $e->getMessage());
        }
    }

    /**
     * Récupère une annonce spécifique par son ID.
     * @param int $id Identifiant de l'annonce.
     * @return array|null Données de l'annonce ou null si non trouvée.
     * @throws Exception En cas d'erreur SQL.
     */
    public function getAnnonceById($id)
    {
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
