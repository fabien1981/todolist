<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/base_de_donnees.php';

use App\Controleur\TacheControleur;

header('Content-Type: application/json');

$controleur = new TacheControleur();

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case 'POST':
        $controleur->gererRequete(); // Ajout d'une tâche
        break;
    case 'GET':
        $controleur->afficherTaches(); // Récupération des tâches
        break;
    case 'PUT':
        parse_str(file_get_contents("php://input"), $putData);
        $controleur->mettreAJourTache($putData); // Mise à jour d'une tâche
        break;
    case 'DELETE':
        parse_str(file_get_contents("php://input"), $deleteData);
        $controleur->supprimerTache($deleteData); // Suppression d'une tâche
        break;
    default:
        http_response_code(405);
        echo json_encode(['message' => 'Méthode non autorisée']);
}
?>
