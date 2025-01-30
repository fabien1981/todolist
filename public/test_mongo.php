<?php
require_once __DIR__ . '/../config/base_de_donnees.php';

$db = obtenirBaseDeDonnees();
echo "Connexion réussie à MongoDB !";
?>
