<?php
// Activer l'affichage des erreurs pour le développement
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Chemin du fichier .env
$envFile = __DIR__ . '/.env';

// Vérifier si le fichier .env existe
if (!file_exists($envFile)) {
    die("Erreur : Le fichier .env est introuvable !");
}

// Charger le fichier .env
$config = parse_ini_file($envFile, true);
var_dump($config);

// Vérifier si les variables sont bien chargées
if (!$config) {
    die("Erreur : Impossible de charger le fichier .env !");
}

// Définir les variables d'environnement (optionnel)
// foreach ($config as $key => $value) {
//     putenv("$key=$value");
// }

// Récupérer les informations de connexion
$host = $config['DB_HOST'] ?? getenv('DB_HOST');
$dbname = $config['DB_NAME'] ?? getenv('DB_NAME');
$username = $config['DB_USER'] ?? getenv('DB_USER');
$password = $config['DB_PASS'] ?? getenv('DB_PASS');

// Vérifier si toutes les variables sont bien définies
if (!$host || !$dbname || !$username || !$password) {
    die("Erreur : Une ou plusieurs variables d'environnement sont manquantes !");
}

// Afficher les variables (DEBUG uniquement, à commenter en production)
// var_dump($host, $dbname, $username, $password);

try {
    // Connexion à MySQL avec PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
?>
