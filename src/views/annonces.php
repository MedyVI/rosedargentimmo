<?php
// Empêcher l'accès direct
if (!defined('SITE_NAME')) {
    die("Accès direct interdit.");
}

require_once __DIR__ . '/../model/AnnoncesModel.php';

try {
    $annoncesModel = new AnnoncesModel($pdo);
    $annonces = $annoncesModel->getAnnonces();
} catch (Exception $e) {
    die($e->getMessage());
}
?>

<section class="annonces">
    <h1>Nos annonces immobilières</h1>
    <div class="annonces-container">
        <?php if (!empty($annonces)): ?>
            <?php foreach ($annonces as $annonce): ?>
                <div class="annonce-card">
                    <img src="<?= IMG_PATH . 'placeholder.jpg' ?>" alt="Image de <?= htmlspecialchars($annonce['titre']) ?>">
                    <h2><?= htmlspecialchars($annonce['titre']) ?></h2>
                    <p class="prix"><?= number_format($annonce['prix'], 0, ',', ' ') ?> €</p>
                    <p><strong>Type :</strong> <?= htmlspecialchars($annonce['type']) ?></p>
                    <p><strong>Surface :</strong> <?= htmlspecialchars($annonce['surface']) ?> m²</p>
                    <p><strong>Ville :</strong> <?= htmlspecialchars($annonce['ville']) ?> (<?= htmlspecialchars($annonce['code_postal']) ?>)</p>
                    <p class="description"><?= htmlspecialchars($annonce['description']) ?></p>
                    <a href="#" class="btn">Voir l'annonce</a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune annonce trouvée.</p>
        <?php endif; ?>
    </div>
</section>
