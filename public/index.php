<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/base_de_donnees.php';

use App\Middlewares\AuthMiddleware;  // ✅ Ajout du bon namespace
use App\Controleur\TacheControleur;

// ✅ Récupérer l'URL demandée
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$controleur = new TacheControleur();

// ✅ Définition des **routes publiques** (aucune vérification de token JWT)
$routesPubliques = [
    '/todolist/public/',
    '/todolist/public/index.php',
    '/todolist/public/connexion.php',
    '/todolist/public/inscription.php'
];

// ✅ Vérifier si la route demandée est publique
if (in_array($path, $routesPubliques)) {
    switch ($path) {
        case '/todolist/public/':
        case '/todolist/public/index.php':
            include __DIR__ . '/../src/Vue/accueil.php'; // ✅ Page d'accueil
            break;
        case '/todolist/public/connexion.php':
            include __DIR__ . '/../src/Vue/connexion.php'; // ✅ Page de connexion
            break;
        case '/todolist/public/inscription.php':
            include __DIR__ . '/../src/Vue/inscription.php'; // ✅ Page d'inscription
            break;
    }
    exit();
}

// ✅ Définition des **routes protégées** (nécessitent un token JWT valide)
$routesProtegees = [
    '/todolist/public/dashboard.php',
    '/todolist/public/listes.php',
    '/todolist/public/creer.php'
];

// ✅ Vérifier si la route demandée est protégée
if (in_array($path, $routesProtegees)) {
    error_log("🔍 Tentative d'accès à " . $path);
    // 🔒 Vérifier l'authentification avec JWT
    $utilisateur = AuthMiddleware::verifierAuthentification();
    error_log("👤 Utilisateur après vérification : " . json_encode($utilisateur));

    if (!$utilisateur) {
        error_log("❌ Accès refusé, redirection vers connexion.php !");
        header("Location: /todolist/public/connexion.php");
        exit();
    }
    error_log("✅ Accès autorisé à $path");
    // ✅ L'utilisateur est authentifié, charger la page demandée
    switch ($path) {
        case '/todolist/public/dashboard.php':
            include __DIR__ . '/../src/Vue/dashboard.php';
            break;
        case '/todolist/public/listes.php':
            include __DIR__ . '/../src/Vue/listes.php';
            break;
        case '/todolist/public/creer.php':
            include __DIR__ . '/../src/Vue/creer.php';
            break;
    }
    exit();
}

// ❌ Si aucune route ne correspond, afficher une erreur 404
http_response_code(404);
echo json_encode(['success' => false, 'message' => '❌ Page non trouvée']);
exit();
