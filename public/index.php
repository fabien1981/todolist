<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/base_de_donnees.php';

use App\Controleur\TacheControleur;

// Définir le chemin demandé
$path = $_SERVER['REQUEST_URI'];
$controleur = new TacheControleur();

// Routeur simplifié
if ($path === '/todolist/public/' || $path === '/todolist/public/index.php') {
    // Affiche la page d'accueil
    include __DIR__ . '/../src/Vue/accueil.php';
} elseif ($path === '/todolist/public/creer.php') {
    // Affiche le formulaire pour créer une nouvelle to-do list
    include __DIR__ . '/../src/Vue/creer.php';
} elseif ($path === '/todolist/public/listes.php') {
    // Affiche les listes existantes
    include __DIR__ . '/../src/Vue/listes.php';
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Gérer les requêtes POST (ex : ajout de tâches)
    $controleur->gererRequete();
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Récupération des tâches via l'API
    $controleur->afficherTaches();
} else {
    // Si la requête ne correspond à rien
    http_response_code(404);
    echo "Page non trouvée.";
}
?>
