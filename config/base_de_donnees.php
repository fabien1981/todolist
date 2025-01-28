<?php
// config/base_de_donnees.php
require_once __DIR__ . '/../vendor/autoload.php';

use MongoDB\Client;

function obtenirBaseDeDonnees() {
    $client = new Client("mongodb://localhost:27017");
    return $client->todolist; // Nom de la base de données
}
?>
