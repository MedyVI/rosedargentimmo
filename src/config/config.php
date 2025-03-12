<?php
// Vérification pour éviter les redéfinitions accidentelles
if (!defined('CONFIG_LOADED')) {
    define('CONFIG_LOADED', true);

    // Charger Composer autoload
    require_once __DIR__ . '/../../vendor/autoload.php';

    // Chargement des variables d'environnement depuis .env
    if (class_exists('Dotenv\Dotenv')) {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
        $dotenv->load();
    } else {
        die("Erreur : Dotenv n'est pas installé. Exécutez 'composer require vlucas/phpdotenv'");
    }

    // Nom du site
    define('SITE_NAME', $_ENV['SITE_NAME'] ?? 'Rose d\'Argent Immobilier');

    // Définition dynamique du chemin de base
    define('BASE_URL', $_ENV['BASE_URL'] ?? $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/');

    // Définition d’un chemin commun pour les assets
    define('ASSETS_PATH', BASE_URL . 'assets/');

    // Chemins vers les différents assets
    define('CSS_PATH', ASSETS_PATH . 'css/');
    define('IMG_PATH', BASE_URL . 'assets/images/');


    define('JS_PATH', ASSETS_PATH . 'js/');
}
?>
