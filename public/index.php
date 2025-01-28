<?php
require_once __DIR__ . '/../config/base_de_donnees.php';
require_once __DIR__ . '/../src/Controleur/TacheControleur.php';
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controleur\TacheControleur;

$controleur = new TacheControleur();

// Route les requêtes
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controleur->gererRequete();
} else {
    $controleur->afficherTaches();
}
?>
