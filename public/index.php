<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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
                echo "
            <div class='error-container'>
                <div class='error-box'>
                    <h1>Oups ! Cette page est introuvable</h1>
                    <p class='error-message'>Il semble que la page que vous recherchez n'existe pas ou a été déplacée.</p>
                    <a href='" . BASE_URL . "index.php?page=accueil' class='error-btn'>Revenir à l'accueil</a>
                </div>
            </div>";
                break;
        }
        ?>
    </main>
</div>

<?php
// Inclusion du footer
require_once __DIR__ . '/../src/views/includes/footer.php';
?>