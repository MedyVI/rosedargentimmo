<?php
require_once __DIR__ . '/../../config/config.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SITE_NAME ?></title>
    <link rel="stylesheet" href="<?= CSS_PATH ?>styles.css">
</head>
<body>
    <header>
        <div class="container">
            <div class="logo-container">
                <a href="<?= BASE_URL ?>">
                    <img src="<?= IMG_PATH ?>LOGO_Rosed'Argent_BLANC_sans_fond.png" alt="Logo Rose d'Argent" class="logo">
                </a>
                <a href="<?= BASE_URL ?>">
                    <img src="<?= IMG_PATH ?>BANDEAU_Rosed'Argent_BLANC_sans_fond.png" alt="Logo Rose d'Argent" class="bandeau">
                </a>
            </div>
            <nav>
                <ul class="header-menu">
                    <li><a href="<?= BASE_URL ?>index.php?page=accueil">Accueil</a></li>
                    <li><a href="<?= BASE_URL ?>index.php?page=annonces">Annonces</a></li>
                    <li><a href="<?= BASE_URL ?>index.php?page=contact">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>
