<?php

class AnnoncesModel
{
    private $pdo;

    /**
     * Constructeur de la classe AnnoncesModel.
     * @param PDO $pdo Instance PDO pour la connexion à la base de données.
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Récupère les annonces paginées avec leurs images associées.
     * @param int $page Numéro de la page actuelle.
     * @param int $annoncesParPage Nombre d'annonces par page.
     * @return array Liste des annonces paginées avec leurs images.
     */
    public function getAnnoncesPaginees(int $page, int $annoncesParPage = 10): array
    {
        try {
            $page = max(1, $page);
            $annoncesParPage = max(1, $annoncesParPage);
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
            $stmt->execute();
    
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new PDOException("Erreur lors de la récupération des annonces paginées : " . $e->getMessage());
        }
    }

    /**
     * Récupère le nombre total d'annonces.
     * @return int Nombre total d'annonces dans la base de données.
     */
    public function countAnnonces(): int
    {
        try {
            $stmt = $this->pdo->query("SELECT COUNT(*) AS total FROM annonces");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int) $result['total'];
        } catch (PDOException $e) {
            throw new PDOException("Erreur lors du comptage des annonces : " . $e->getMessage());
        }
    }

    /**
     * Récupère une annonce spécifique par son ID.
     * @param int $id Identifiant de l'annonce.
     * @return array|null Données de l'annonce ou null si non trouvée.
     */
    public function getAnnonceById(int $id): ?array
    {
        try {
            if ($id <= 0) {
                throw new InvalidArgumentException("ID invalide.");
            }

            $stmt = $this->pdo->prepare("
                SELECT 
                    a.*, 
                    (SELECT GROUP_CONCAT(i.url) FROM images i WHERE i.annonce_id = a.id) AS images
                FROM annonces a
                WHERE a.id = :id
            ");
            $stmt->execute(['id' => $id]);
            $annonce = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($annonce && isset($annonce['images'])) {
                $annonce['images'] = explode(',', $annonce['images']); // Convertir en tableau
            }

            return $annonce ?: null;
        } catch (PDOException $e) {
            throw new PDOException("Erreur lors de la récupération de l'annonce : " . $e->getMessage());
        }
    }
}
