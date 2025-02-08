<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config/base_de_donnees.php';
require_once __DIR__ . '/../middlewares/AuthMiddleware.php';

use App\Controleur\TacheControleur;
use App\Controleur\InscriptionControleur;
use App\Controleur\ConnexionControleur;
use App\Middlewares\AuthMiddleware; 

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

try {
    switch ($action) {

        // ✅ INSCRIPTION UTILISATEUR (PAS BESOIN DE TOKEN)
        case 'inscription':
            $controleur = new InscriptionControleur();
            $input = json_decode(file_get_contents("php://input"), true);

            if (!$input || empty($input['email']) || empty($input['motDePasse'])) {
                throw new Exception('❌ Données invalides ou manquantes');
            }

            $resultat = $controleur->inscription($input['email'], $input['motDePasse']);
            echo json_encode($resultat);
            exit();

        // ✅ CONNEXION UTILISATEUR (GÉNÈRE UN TOKEN)
       // ✅ CONNEXION UTILISATEUR
case 'connexion':
    if ($method === 'POST') {
        require_once __DIR__ . '/../src/Controleur/ConnexionControleur.php';
        $controleur = new ConnexionControleur();

        // 🔍 Debug : Vérifier si les données arrivent
        error_log("📥 Données reçues (brutes) : " . file_get_contents("php://input"));

        $input = json_decode(file_get_contents("php://input"), true);

        // 🔍 Vérifier si les données sont bien envoyées et reçues
        if (!$input || !isset($input['email']) || !isset($input['motDePasse'])) {
            error_log("❌ Données manquantes !");
            echo json_encode(['success' => false, 'message' => '❌ Données manquantes']);
            exit();
        }

        try {
            $email = filter_var($input['email'], FILTER_SANITIZE_EMAIL);
            $motDePasse = trim($input['motDePasse']);

            // ✅ Vérifier si l'email et le mot de passe sont valides
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($motDePasse)) {
                error_log(" Format email ou mot de passe invalide !");
                echo json_encode(['success' => false, 'message' => ' Format email ou mot de passe invalide']);
                exit();
            }

            $resultat = $controleur->connexion($email, $motDePasse);

            // 🔍 Debug : Afficher la réponse API avant envoi
            error_log("✅ Réponse API : " . json_encode($resultat));

            // ✅ Vérifier si la connexion est réussie
            if ($resultat['success']) {
                // ✅ Envoyer le token JWT
                echo json_encode([
                    'success' => true,
                    'token' => $resultat['token'],
                    'message' => '✅ Connexion réussie !'
                ]);
            } else {
                // ❌ Mauvais identifiants
                echo json_encode([
                    'success' => false,
                    'message' => '❌ Identifiants incorrects'
                ]);
            }
            exit();

        } catch (Exception $e) {
            // ❌ Gestion des erreurs inattendues
            error_log("❌ Erreur connexion API : " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => '❌ Erreur interne']);
            exit();
        }
    }
    break;

        

        

        // ✅ DÉCONNEXION UTILISATEUR (CÔTÉ CLIENT)
        case 'deconnexion':
            echo json_encode([
                'success' => true,
                'message' => 'Déconnecté avec succès',
                'redirect' => '/todolist/public/index.php'
            ]);
            exit();

        // ✅ PROTÉGER LES ROUTES AVEC JWT
        case 'obtenir_tache':
            $utilisateur = AuthMiddleware::verifierAuthentification();
            $controleur = new TacheControleur();

            if (!isset($_GET['id'])) {
                throw new Exception('❌ ID manquant');
            }

            echo json_encode($controleur->obtenirTacheEtItems($_GET['id']));

            exit();

       // ✅ RÉCUPÉRATION D'UNE TÂCHE ET DE SES ITEMS
case 'obtenir_tache_et_items':
    $controleur = new TacheControleur();

    if (!isset($_GET['id'])) {
        throw new Exception('❌ ID de tâche manquant');
    }

    echo json_encode($controleur->obtenirTacheEtItems($_GET['id']));
    exit();

        // ✅ AJOUT D'UNE NOUVELLE LISTE (PROTÉGÉE)
        case 'ajouter_todolist':
            $utilisateur = AuthMiddleware::verifierAuthentification();
            $controleur = new TacheControleur();

            $donnees = json_decode(file_get_contents("php://input"), true);

            if (!$donnees || empty($donnees['titre']) || empty($donnees['description']) || empty($donnees['priorite'])) {
                throw new Exception('❌ Données incomplètes');
            }

            echo json_encode($controleur->ajouterTache($donnees));
            exit();

        // ✅ RÉCUPÉRATION DES LISTES (PROTÉGÉE)
        case 'taches':
            $utilisateur = AuthMiddleware::verifierAuthentification();
            $controleur = new TacheControleur();
        
            if ($method === 'GET') {
                echo json_encode($controleur->obtenirTaches()); // ✅ CORRIGÉ : récupère toutes les tâches
                exit();
            }
            break;
        

        // ✅ SUPPRESSION D'UNE LISTE (PROTÉGÉE)
        case 'supprimer_todolist':
            $utilisateur = AuthMiddleware::verifierAuthentification();
            $controleur = new TacheControleur();

            $donnees = json_decode(file_get_contents("php://input"), true);

            if (empty($donnees['id'])) {
                throw new Exception('❌ ID de liste manquant');
            }

            echo json_encode($controleur->supprimerTache($donnees['id']));
            exit();

        // ✅ MODIFICATION D'UNE LISTE (PROTÉGÉE)
        case 'modifier_todolist':
            $donnees = json_decode(file_get_contents("php://input"), true);
        
            // 🔍 Log pour vérifier les données reçues
            error_log("📥 Données reçues pour mise à jour : " . json_encode($donnees, JSON_PRETTY_PRINT));
        
            if (empty($donnees['id'])) {
                throw new Exception('❌ ID de la tâche manquant');
            }
        
            $controleur = new TacheControleur();
            echo json_encode($controleur->mettreAJourTache($donnees['id'], $donnees));
            exit();
        
        
        // ✅ MISE À JOUR DU STATUT D'UNE TÂCHE (PROTÉGÉE)
        case 'mettre_a_jour_statut':
            $utilisateur = AuthMiddleware::verifierAuthentification();
            $controleur = new TacheControleur();

            $donnees = json_decode(file_get_contents("php://input"), true);

            if (empty($donnees['id']) || empty($donnees['statut'])) {
                throw new Exception('❌ Données incomplètes');
            }

            if (isset($donnees['id']) && isset($donnees['statut'])) {
                echo json_encode($controleur->mettreAJourStatut($donnees['id'], $donnees['statut']));
            } else {
                echo json_encode(['success' => false, 'message' => '❌ Données incomplètes']);
            }
            
            exit();

        // ❌ ACTION NON RECONNUE
        default:
            throw new Exception('❌ Action non reconnue');
    }
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    exit();
}
