<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
var_dump(getenv('DB_USERNAME'), getenv('DB_PASSWORD'));

// Inclusion de la configuration globale
require_once __DIR__ . '/../src/config/config.php';

// Détermination de la page demandée
$page = $_GET['page'] ?? 'home';

// Inclusion du header
require_once __DIR__ . '/../src/views/includes/header.php';
?>

<div class="main-container">
    <main class="container">
        <?php
        // Gestion du routage des pages
        switch ($page) {
            case 'accueil':
                require __DIR__ . '/../src/views/accueil.php';
                break;

            case 'annonces':
                require __DIR__ . '/../src/views/annonces.php';
                break;

            case 'contact':
                require __DIR__ . '/../src/views/contact.php';
                break;

            default:
                echo "<h1>Page introuvable</h1>";
                break;
        }
        ?>
    </main>
</div>

<?php
// Inclusion du footer
require_once __DIR__ . '/../src/views/includes/footer.php';
?>