<?php
require_once 'database.php';

try {
    // Connection test
    $pdo->query("SELECT 1");
    echo "Connexion OK";
} catch (PDOException $e) {
    echo "No connection";
}

?>