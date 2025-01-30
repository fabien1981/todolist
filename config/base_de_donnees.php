<?php
require_once __DIR__ . '/../vendor/autoload.php';

use MongoDB\Client;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

function obtenirBaseDeDonnees() {
    $uri = $_ENV['MONGO_URI'];
    $dbName = $_ENV['MONGO_DB'];

    $client = new Client($uri);
    return $client->$dbName; // Connexion à la base spécifiée
}
?>
