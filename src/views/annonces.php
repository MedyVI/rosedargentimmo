<?php
require_once __DIR__ . '/../controllers/AnnoncesController.php';

$controller = new AnnoncesController($pdo);

// Récupérer le numéro de la page et s'assurer qu'il est valide
$page = max(1, isset($_GET['num']) ? (int)$_GET['num'] : 1);
$resultats = $controller->afficherAnnonces($page);

$annonces = $resultats['annonces'];
$totalPages = max(1, $resultats['totalPages']);
$page = min($page, $totalPages);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des annonces</title>
    <link rel="stylesheet" href="<?= CSS_PATH ?>main.css">
</head>

<body>
    <main class="container">
        <h1 class="h1annonces">Nos annonces</h1>

        <div class="annonces-container">
            <?php if (!empty($annonces)): ?>
                <?php foreach ($annonces as $annonce): ?>
                    <div class="annonce-card">
                        <img src="<?= BASE_URL . htmlspecialchars($annonce['image_url']) ?>" alt="Image du bien" class="annonce-image">
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
            <!-- Bouton précédent -->
            <?php if ($page > 1): ?>
                <a href="index.php?page=annonces&num=<?= max(1, $page - 1) ?>" class="btn-pagination">◀</a>
            <?php endif; ?>

            <!-- Numéros de page -->
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="index.php?page=annonces&num=<?= $i ?>" class="btn-num <?= ($i == $page) ? 'active' : '' ?>">
                    <?= $i ?>
                </a>
            <?php endfor; ?>

            <!-- Bouton suivant -->
            <?php if ($page < $totalPages): ?>
                <a href="index.php?page=annonces&num=<?= min($totalPages, $page + 1) ?>" class="btn-pagination">▶</a>
            <?php endif; ?>
        </div>
</body>

</html>