<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// echo "Chargement de index.php<br>";
// die();

require_once __DIR__ . '/../src/config/config.php';

$page = $_GET['page'] ?? 'home';

switch ($page) {
    case 'annonces':
        require __DIR__ . '/../src/views/annonces.php';
        break;
    default:
        echo "<h1>Bienvenue sur Rose d'Argent Immobilier</h1>";
        echo '<a href="?page=annonces" style="display: inline-block; padding: 10px; background: #007bff; color: white; text-decoration: none; border-radius: 5px;">Voir les annonces</a>';
        break;
}
?>
