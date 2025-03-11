<?php
require_once __DIR__ . '/../model/AnnoncesModel.php';

try {
    $annoncesModel = new AnnoncesModel($pdo);

    // Déterminer la page actuelle (1 par défaut)
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $page = max(1, $page);
    $annoncesParPage = 10;
    // var_dump($page, $annoncesParPage);
    // die();
    
    // Récupérer les annonces paginées
    $annonces = $annoncesModel->getAnnoncesPaginees($page, $annoncesParPage);

    // Récupérer le nombre total d'annonces pour la pagination
    $totalAnnonces = $annoncesModel->countAnnonces();
    $totalPages = ceil($totalAnnonces / $annoncesParPage);

} catch (Exception $e) {
    die($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des annonces</title>
    <link rel="stylesheet" href="<?= CSS_PATH ?>styles.css">
</head>
<body>
    <main class="container">
        <h1 class="h1annonces">Nos annonces</h1>
        
        <div class="annonces-container">
            <?php if ($annonces && count($annonces) > 0): ?>
                <?php foreach ($annonces as $annonce): ?>
                    <div class="annonce-card">
                        <img src="<?= BASE_URL . $annonce['image_url'] ?>" alt="Image du bien" class="annonce-image">
                        <div class="annonce-content">
                            <h2><?= htmlspecialchars($annonce['titre']) ?></h2>
                            <p><strong>Type :</strong> <?= htmlspecialchars($annonce['type']) ?></p>
                            <p><strong>Prix :</strong> <?= number_format($annonce['prix'], 2, ',', ' ') ?> €</p>
                            <p><strong>Surface :</strong> <?= htmlspecialchars($annonce['surface']) ?> m²</p>
                            <p><strong>Ville :</strong> <?= htmlspecialchars($annonce['ville']) ?></p>
                            <p><strong>Statut :</strong> <?= htmlspecialchars($annonce['statut']) ?></p>
                            <a href="#" class="btn">Voir l'annonce</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Aucune annonce trouvée.</p>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>" class="btn-pagination">Précédent</a>
            <?php endif; ?>
            <span>Page <?= $page ?> / <?= $totalPages ?></span>
            <?php if ($page < $totalPages): ?>
                <a href="?page=<?= $page + 1 ?>" class="btn-pagination">Suivant</a>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>
