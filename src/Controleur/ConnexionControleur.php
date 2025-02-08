<?php
namespace App\Controleur;

require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../config/base_de_donnees.php';
require_once __DIR__ . '/../../src/Modele/UtilisateurModele.php';
require_once __DIR__ . '/../../config/securite.php';

use App\Modele\UtilisateurModele;
use Firebase\JWT\JWT;

class ConnexionControleur {
    private $modele;

    public function __construct() {
        $db = obtenirBaseDeDonnees();
        $this->modele = new UtilisateurModele($db);
    }

    public function connexion($email, $motDePasse) {
        error_log("📥 Tentative de connexion pour: " . $email);
        $utilisateur = $this->modele->trouverParEmail($email);
        
        if (!$utilisateur || !password_verify($motDePasse, $utilisateur['motDePasse'])) {
            error_log("❌ Identifiants incorrects !");
            echo json_encode(['success' => false, 'message' => '❌ Identifiants incorrects']);
            exit();
        }

        // ✅ Vérification de la clé JWT
        error_log("🔑 Clé JWT utilisée dans ConnexionControleur : " . JWT_SECRET);

        // ✅ Générer un token JWT avec email et ID
        $payload = [
            "id" => $utilisateur['_id'], // ou $utilisateur['id'] selon ta base de données
            "email" => $utilisateur['email'],
            "iat" => time(),
            "exp" => time() + 3600 // Expire dans 1 heure
        ];

        $token = JWT::encode($payload, JWT_SECRET, JWT_ALGO);
        error_log("✅ Token JWT généré : " . $token);

        echo json_encode(['success' => true, 'token' => $token]);
        exit();
    }
}
