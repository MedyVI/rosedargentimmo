<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/src/config/database.php';

/**
 * Récupérer les types autorisés depuis la base de données (colonne ENUM 'type').
 *
 * @param PDO $pdo
 * @return array
 */
function getAllowedTypes($pdo) {
    $query = "SHOW COLUMNS FROM annonces LIKE 'type'";
    $stmt = $pdo->query($query);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    // Extraction des valeurs ENUM
    if ($result) {
        preg_match("/^enum\((.*)\)$/", $result['Type'], $matches);
        if (isset($matches[1])) {
            // Convertit la liste de valeurs en un tableau
            $types = str_getcsv($matches[1], ",", "'");
            return $types;
        }
    }
    return [];
}

/**
 * Générer une référence unique aléatoire (REF + nombre).
 *
 * @param PDO $pdo
 * @return string
 */
function generateUniqueReference($pdo) {
    do {
        // Génère une référence entre REF1000 et REF99999
        $reference = "REF" . rand(1000, 99999);

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM annonces WHERE reference = ?");
        $stmt->execute([$reference]);
        $exists = $stmt->fetchColumn();
    } while ($exists > 0);

    return $reference;
}

/**
 * Générer un prix aléatoire entre 100 000 et 1 000 000 €.
 *
 * @return int
 */
function randomPrice() {
    return rand(100000, 1000000);
}

/**
 * Générer une surface aléatoire entre 20 et 300 m².
 *
 * @return int
 */
function randomSurface() {
    return rand(20, 300);
}

/**
 * Générer des coordonnées GPS aléatoires (dans un cadre approximatif de la France).
 *
 * @return string Format "latitude,longitude"
 */
function randomGPS() {
    // Définir une bounding box pour la France métropolitaine (approximative)
    $minLat = 42.28;  // Sud
    $maxLat = 51.09;  // Nord
    $minLon = -5.45;  // Ouest
    $maxLon = 9.86;   // Est

    // Génération de valeurs aléatoires flottantes
    $lat = randFloat($minLat, $maxLat);
    $lon = randFloat($minLon, $maxLon);

    // Retourne la coordonnée sous forme "lat,lon"
    return sprintf("%.5f,%.5f", $lat, $lon);
}

/**
 * Génère un float aléatoire entre $min et $max.
 *
 * @param float $min
 * @param float $max
 * @return float
 */
function randFloat($min, $max) {
    return $min + mt_rand() / mt_getrandmax() * ($max - $min);
}

/**
 * Générer une ville aléatoire.
 *
 * @return string
 */
function randomCity() {
    $villes = ["Épinal", "Nancy", "Metz", "Strasbourg", "Dijon", "Lyon", "Paris", "Marseille", "Bordeaux"];
    return $villes[array_rand($villes)];
}

/**
 * Générer un code postal aléatoire.
 *
 * @return string
 */
function randomPostalCode() {
    // Code postal français : 5 chiffres (hors DOM-TOM complexité)
    return str_pad((string)rand(1000, 99999), 5, "0", STR_PAD_LEFT);
}

/**
 * Choisir un type de bien aléatoire depuis la liste autorisée (ENUM).
 *
 * @param array $allowedTypes
 * @return string
 */
function randomType($allowedTypes) {
    return $allowedTypes[array_rand($allowedTypes)];
}

/**
 * Générer une description aléatoire.
 *
 * @return string
 */
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

try {
    // 1. Récupérer les types valides depuis MySQL (colonne ENUM 'type')
    $allowedTypes = getAllowedTypes($pdo);

    if (empty($allowedTypes)) {
        die("❌ Erreur : Impossible de récupérer les valeurs autorisées pour 'type'.");
    }

    // 2. Insérer 50 annonces factices
    for ($i = 1; $i <= 50; $i++) {
        $titre            = "Annonce #" . $i;
        $reference        = generateUniqueReference($pdo);
        $prix             = randomPrice();
        $description      = randomDescription();
        $type             = randomType($allowedTypes);
        $surface          = randomSurface();
        $ville            = randomCity();
        $code_postal      = randomPostalCode();
        $localisation     = randomGPS(); // Coordonnées GPS
        $statut           = (rand(0, 1) === 1) ? "disponible" : "vendu";
        $date_publication = date("Y-m-d H:i:s", strtotime("-" . rand(1, 365) . " days"));

        // Préparation de la requête d'insertion
        $query = "
            INSERT INTO annonces (
                titre,
                reference,
                description,
                prix,
                type,
                surface,
                localisation,   -- maintenant des coordonnées GPS
                code_postal,
                ville,
                statut,
                date_publication
            ) VALUES (
                :titre,
                :reference,
                :description,
                :prix,
                :type,
                :surface,
                :localisation,
                :code_postal,
                :ville,
                :statut,
                :date_publication
            )
        ";

        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'titre'            => $titre,
            'reference'        => $reference,
            'description'      => $description,
            'prix'             => $prix,
            'type'             => $type,
            'surface'          => $surface,
            'localisation'     => $localisation,
            'code_postal'      => $code_postal,
            'ville'            => $ville,
            'statut'           => $statut,
            'date_publication' => $date_publication
        ]);
    }

    echo "✅ 50 annonces factices insérées avec succès !";
} catch (PDOException $e) {
    die("❌ Erreur lors de l'insertion : " . $e->getMessage());
}
