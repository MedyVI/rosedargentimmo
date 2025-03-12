<?php
// Activer l'affichage des erreurs pour le développement (à désactiver en production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Vérifier si Dotenv est bien chargé
if (!isset($_ENV['DB_HOST'])) {
    die("Erreur : Les variables d'environnement ne sont pas chargées. Vérifiez `config.php`.");
}

// Récupérer les informations de connexion depuis .env
$host = $_ENV['DB_HOST'] ?? null;
$dbname = $_ENV['DB_NAME'] ?? null;
$username = $_ENV['DB_USER'] ?? null;
$password = $_ENV['DB_PASS'] ?? null;

// Vérifier si toutes les variables sont bien définies
if (!$host || !$dbname || !$username || !$password) {
    die("Erreur : Une ou plusieurs variables d'environnement sont manquantes !");
}

try {
    // Connexion à MySQL avec PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,   // Activer les exceptions
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Mode de récupération par défaut
        PDO::ATTR_EMULATE_PREPARES => false,   // Désactiver l'émulation des requêtes préparées
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
