<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/src/config/database.php';

/**
 * Récupérer les types autorisés depuis la base de données (colonne ENUM 'type').
 */
function getAllowedTypes($pdo) {
    $query = "SHOW COLUMNS FROM annonces LIKE 'type'";
    $stmt = $pdo->query($query);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result) {
        preg_match("/^enum\\((.*)\\)$/", $result['Type'], $matches);
        if (isset($matches[1])) {
            return str_getcsv($matches[1], ",", "'");
        }
    }
    return [];
}

function generateUniqueReference($pdo) {
    do {
        $reference = "REF" . rand(1000, 99999);
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM annonces WHERE reference = ?");
        $stmt->execute([$reference]);
        $exists = $stmt->fetchColumn();
    } while ($exists > 0);
    return $reference;
}

function randomPrice() { return rand(100000, 1000000); }
function randomSurface() { return rand(20, 300); }
function randFloat($min, $max) { return $min + mt_rand() / mt_getrandmax() * ($max - $min); }
function randomGPS() {
    return sprintf("%.5f,%.5f", randFloat(42.28, 51.09), randFloat(-5.45, 9.86));
}
function randomCity() {
    $villes = ["Épinal", "Nancy", "Metz", "Strasbourg", "Dijon", "Lyon", "Paris", "Marseille", "Bordeaux"];
    return $villes[array_rand($villes)];
}
function randomPostalCode() { return str_pad(rand(1000, 99999), 5, "0", STR_PAD_LEFT); }
function randomType($allowedTypes) { return $allowedTypes[array_rand($allowedTypes)]; }
function randomDescription() {
    $descriptions = [
        "Maison spacieuse avec jardin et garage.",
        "Appartement lumineux avec balcon et vue dégagée.",
        "Loft moderne avec grandes baies vitrées.",
        "Local commercial idéalement situé en centre-ville.",
        "Villa avec piscine et terrain arboré.",
        "Studio meublé proche de toutes commodités.",
        "Duplex refait à neuf avec terrasse privative.",
        "Charmant pavillon à proximité des écoles et commerces."
    ];
    return $descriptions[array_rand($descriptions)];
}

function getRandomImage() {
    $imageDir = __DIR__ . "/public/assets/images/maisons_test/";
    $images = glob($imageDir . "*.{jpg,jpeg,png,gif}", GLOB_BRACE);
    return $images ? basename($images[array_rand($images)]) : null;
}

try {
    $allowedTypes = getAllowedTypes($pdo);
    if (empty($allowedTypes)) die("Erreur : Impossible de récupérer les valeurs autorisées pour 'type'.");

    for ($i = 1; $i <= 50; $i++) {
        $titre = "Annonce #" . $i;
        $reference = generateUniqueReference($pdo);
        $prix = randomPrice();
        $description = randomDescription();
        $type = randomType($allowedTypes);
        $surface = randomSurface();
        $ville = randomCity();
        $code_postal = randomPostalCode();
        $localisation = randomGPS();
        $statut = (rand(0, 1) === 1) ? "disponible" : "vendu";
        $date_publication = date("Y-m-d H:i:s", strtotime("-" . rand(1, 365) . " days"));

        $query = "INSERT INTO annonces (titre, reference, description, prix, type, surface, localisation, code_postal, ville, statut, date_publication) 
                  VALUES (:titre, :reference, :description, :prix, :type, :surface, :localisation, :code_postal, :ville, :statut, :date_publication)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'titre' => $titre,
            'reference' => $reference,
            'description' => $description,
            'prix' => $prix,
            'type' => $type,
            'surface' => $surface,
            'localisation' => $localisation,
            'code_postal' => $code_postal,
            'ville' => $ville,
            'statut' => $statut,
            'date_publication' => $date_publication
        ]);
        
        $annonce_id = $pdo->lastInsertId();
        $image = getRandomImage();
        if ($image) {
            $imageQuery = "INSERT INTO images (annonce_id, url) VALUES (:annonce_id, :url)";
            $imageStmt = $pdo->prepare($imageQuery);
            $imageStmt->execute(['annonce_id' => $annonce_id, 'url' => "public/assets/images/maisons_test/" . $image]);
        }
    }

    echo "50 annonces factices avec images insérées avec succès !";
} catch (PDOException $e) {
    die("Erreur lors de l'insertion : " . $e->getMessage());
}
