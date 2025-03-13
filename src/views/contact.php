<?php
// Activation du mode debug pour voir les erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

// Chargement de PHPMailer et Dotenv via Composer
require __DIR__ . '/../../vendor/autoload.php';

// Chargement des variables d'environnement
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Initialisation des variables
$name = $email = $phone = $message = "";
$errors = [];
$successMessage = "";

// Débogage - Vérifier ce qui est reçu via POST
// var_dump($_POST);

// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Nettoyage et validation des champs
    if (empty(trim($_POST["name"]))) {
        $errors[] = "Le champ Nom et Prénom est requis.";
    } else {
        $name = htmlspecialchars(trim($_POST["name"]));
    }

    if (empty(trim($_POST["email"]))) {
        $errors[] = "Le champ Email est requis.";
    } elseif (!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'adresse email est invalide.";
    } else {
        $email = htmlspecialchars(trim($_POST["email"]));
    }

    if (!empty(trim($_POST["phone"]))) {
        if (!preg_match('/^(0[67])\d{8}$/', $_POST["phone"])) {
            $errors[] = "Le numéro de téléphone doit commencer par 06 ou 07 et contenir 10 chiffres.";
        } else {
            $phone = htmlspecialchars(trim($_POST["phone"]));
        }
    }

    if (empty(trim($_POST["message"]))) {
        $errors[] = "Le champ Message est requis.";
    } else {
        $message = htmlspecialchars(trim($_POST["message"]));
    }

    // Si aucune erreur, envoi de l'email avec PHPMailer
    if (empty($errors)) {
        $mail = new PHPMailer(true);

        try {
            // Configuration du serveur SMTP
            $mail->isSMTP();
            $mail->Host       = $_ENV['SMTP_HOST'];
            $mail->SMTPAuth   = true;
            $mail->Username   = $_ENV['SMTP_USER'];
            $mail->Password   = $_ENV['SMTP_PASS'];
            $mail->SMTPSecure = $_ENV['SMTP_SECURE'];
            $mail->Port       = $_ENV['SMTP_PORT'];

            // Configuration de l'email
            $mail->setFrom($email, $name);
            $mail->addAddress($_ENV['SMTP_USER']);
            $mail->Subject = "Nouveau message de contact - Rose d'Argent Immobilier";
            $mail->Body    = "Nom: $name\nEmail: $email\nTéléphone: $phone\n\nMessage:\n$message";

            // Debug - Vérifier que l'email est bien pris en compte
            // var_dump("Email validé :", $email);

            // Envoi de l'email
            if ($mail->send()) {
                $successMessage = "Votre message a bien été envoyé.";
            } else {
                $errors[] = "Une erreur est survenue lors de l'envoi du message.";
            }
        } catch (Exception $e) {
            error_log("Erreur d'envoi de l'email : " . $mail->ErrorInfo);
            $errors[] = "Une erreur est survenue lors de l'envoi du message. Veuillez réessayer plus tard.";
        }
    }
}
?>

<div class="contact-section">
    <div class="container">
        <h1>Contactez-nous</h1>
        <p class="contact-description">
            Une question ? Besoin d'informations ? Remplissez le formulaire ci-dessous.
        </p>

        <!-- Affichage des erreurs -->
        <?php if (!empty($errors)): ?>
            <div class="error-message">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <!-- Affichage du message de succès -->
        <?php if (!empty($successMessage)): ?>
            <div class="success-message"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <form action="" method="POST" class="contact-form">
            <div class="form-group">
                <label for="name">Nom et Prénom *</label>
                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>

            <div class="form-group">
                <label for="phone">Téléphone (optionnel)</label>
                <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($phone); ?>">
            </div>

            <div class="form-group">
                <label for="message">Votre message *</label>
                <textarea id="message" name="message" rows="5" required><?php echo htmlspecialchars($message); ?></textarea>
            </div>

            <button type="submit" class="btn">Envoyer</button>
        </form>
    </div>
</div>
