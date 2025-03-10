<?php
require_once '../src/config/database.php';

try {
    // Récupération des annonces en mode associatif
    $query = $pdo->query("SELECT * FROM annonces ORDER BY date_publication DESC");
    $annonces = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des annonces : " . $e->getMessage());
}
?>

<hr>
<a href="index.php" style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; font-size: 16px;">
    Index
</a>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des annonces</title>
</head>
<body>
    <h1>Liste des annonces immobilières</h1>

    <?php if ($annonces && count($annonces) > 0): ?>
        <ul>
            <?php foreach ($annonces as $annonce): ?>
                <li>
                    <h2><?= htmlspecialchars($annonce['titre']) ?></h2>
                    <p><strong>Référence :</strong> <?= htmlspecialchars($annonce['reference']) ?></p>
                    <p><strong>Prix :</strong> <?= number_format($annonce['prix'], 2, ',', ' ') ?> €</p>
                    <p><strong>Type :</strong> <?= htmlspecialchars($annonce['type']) ?></p>
                    <p><strong>Surface :</strong> <?= htmlspecialchars($annonce['surface']) ?> m²</p>

                    <!-- Affichage ville et code postal -->
                    <p>
                        <strong>Ville :</strong>
                        <?= htmlspecialchars($annonce['ville']) ?>
                        (<?= htmlspecialchars($annonce['code_postal']) ?>)
                    </p>

                    <p><strong>Description :</strong> <?= htmlspecialchars($annonce['description']) ?></p>
                    <!-- Affichage des coordonnées GPS stockées dans "localisation" -->
                    <p>
                        <strong>Coordonnées GPS :</strong>
                        <?= htmlspecialchars($annonce['localisation']) ?>
                    </p>

                    <p><strong>Statut :</strong> <?= htmlspecialchars($annonce['statut']) ?></p>
                    <p><strong>Date de publication :</strong> <?= htmlspecialchars($annonce['date_publication']) ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucune annonce trouvée.</p>
    <?php endif; ?>

</body>
</html>
