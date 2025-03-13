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

        <!-- Formulaire de filtrage -->
         <!-- Bouton pour ouvrir la modale -->
    <button class="open-modal-btn" onclick="openModal()">Filtrer les annonces</button>

<!-- Modale -->
<div id="filterModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal()">&times;</span>

        <form method="GET" action="index.php" class="filter-form">
            <input type="hidden" name="page" value="annonces">

            <label for="ville">Ville :</label>
            <input type="text" name="ville" id="ville" value="<?= isset($_GET['ville']) ? htmlspecialchars($_GET['ville']) : '' ?>">

            <label for="type">Type :</label>
            <select name="type" id="type">
                <option value="">Tous</option>
                <option value="Maison" <?= (isset($_GET['type']) && $_GET['type'] === 'Maison') ? 'selected' : '' ?>>Maison</option>
                <option value="Appartement" <?= (isset($_GET['type']) && $_GET['type'] === 'Appartement') ? 'selected' : '' ?>>Appartement</option>
            </select>

            <label for="prix_min">Prix Min :</label>
            <input type="number" name="prix_min" id="prix_min" min="0" value="<?= isset($_GET['prix_min']) ? (int)$_GET['prix_min'] : '' ?>">

            <label for="prix_max">Prix Max :</label>
            <input type="number" name="prix_max" id="prix_max" min="0" value="<?= isset($_GET['prix_max']) ? (int)$_GET['prix_max'] : '' ?>">

            <label for="statut">Statut :</label>
            <select name="statut" id="statut">
                <option value="">Tous</option>
                <option value="Disponible" <?= (isset($_GET['statut']) && $_GET['statut'] === 'Disponible') ? 'selected' : '' ?>>Disponible</option>
                <option value="Vendu" <?= (isset($_GET['statut']) && $_GET['statut'] === 'Vendu') ? 'selected' : '' ?>>Vendu</option>
            </select>

            <label for="annoncesParPage">Annonces par page :</label>
            <select name="annoncesParPage" id="annoncesParPage">
                <option value="5" <?= (isset($_GET['annoncesParPage']) && $_GET['annoncesParPage'] == 5) ? 'selected' : '' ?>>5</option>
                <option value="10" <?= (isset($_GET['annoncesParPage']) && $_GET['annoncesParPage'] == 10) ? 'selected' : '' ?>>10</option>
                <option value="20" <?= (isset($_GET['annoncesParPage']) && $_GET['annoncesParPage'] == 20) ? 'selected' : '' ?>>20</option>
            </select>

            <button type="submit">Appliquer les filtres</button>
        </form>
    </div>
</div>

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