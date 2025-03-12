<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../model/AnnoncesModel.php';

class AnnoncesController
{
    private $annoncesModel;

    public function __construct(PDO $pdo)
    {
        $this->annoncesModel = new AnnoncesModel($pdo);
    }

    /**
     * Récupérer les annonces paginées.
     * @param int $page Numéro de la page actuelle.
     * @param int $annoncesParPage Nombre d'annonces par page.
     * @return array Données des annonces.
     */
    public function afficherAnnonces($page = 1, $annoncesParPage = 8)
    {
        $page = max(1, (int) $page);

        try {
            $annonces = $this->annoncesModel->getAnnoncesPaginees($page, $annoncesParPage);
            $totalAnnonces = $this->annoncesModel->countAnnonces();
            $totalPages = max(1, ceil($totalAnnonces / $annoncesParPage));

            return [
                'annonces' => $annonces,
                'totalPages' => $totalPages,
                'page' => $page
            ];
        } catch (Exception $e) {
            die("Erreur : " . $e->getMessage());
        }
    }
}

