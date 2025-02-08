<?php
require_once __DIR__ . '/../config/base_de_donnees.php';

try {
    $db = obtenirBaseDeDonnees();
    echo "✅ Connexion réussie à MongoDB !";
} catch (Exception $e) {
    echo "❌ Erreur de connexion : " . $e->getMessage();
}
