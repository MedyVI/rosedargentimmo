<?php
require_once __DIR__ . '/../../config/config.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Votre expert immobilier à Saint-Maximin-La-Sainte-Baume. Découvrez nos annonces sélectionnées.">
    <meta name="author" content="Rose d'Argent Immobilier">
    <meta name="keywords" content="immobilier, achat, vente, location, maison, appartement">
    <meta property="og:title" content="<?= SITE_NAME ?>">
    <meta property="og:description" content="Découvrez nos meilleures offres immobilières.">
    <meta property="og:image" content="<?= IMG_PATH ?>LOGO_RosedArgent_BLANC_sans_fond.png">
    
    <title><?= SITE_NAME ?></title>
    
    <!-- CSS Principal -->
    <link rel="stylesheet" href="<?= CSS_PATH ?>main.css">
    
    <!-- Chargement conditionnel de CSS supplémentaire -->
    <?php if (isset($_GET['page']) && file_exists(__DIR__ . "/../../public/assets/css/{$_GET['page']}.css")): ?>
        <link rel="stylesheet" href="<?= CSS_PATH . $_GET['page'] ?>.css">
    <?php endif; ?>
</head>
<body>
<header>
    <div class="container">
        <div class="logo-container">
            <a href="<?= BASE_URL ?>index.php?page=contact">
                <img src="<?= IMG_PATH ?>LOGO_RosedArgent_BLANC_sans_fond.png" alt="Logo Rose d'Argent" class="logo">
            </a>
            <a href="<?= BASE_URL ?>index.php?page=accueil">
                <img src="<?= IMG_PATH ?>BANDEAU_RosedArgent_BLANC_sans_fond.png" alt="Bandeau Rose d'Argent" class="bandeau">
            </a>
        </div>
        <nav>
            <ul class="header-menu">
                <li><a href="<?= BASE_URL ?>index.php?page=accueil" class="<?= ($_GET['page'] ?? '') === 'accueil' ? 'active' : '' ?>">Accueil</a></li>
                <li><a href="<?= BASE_URL ?>index.php?page=annonces" class="<?= ($_GET['page'] ?? '') === 'annonces' ? 'active' : '' ?>">Annonces</a></li>
                <li><a href="<?= BASE_URL ?>index.php?page=contact" class="<?= ($_GET['page'] ?? '') === 'contact' ? 'active' : '' ?>">Contact</a></li>
            </ul>
        </nav>
    </div>
</header>
