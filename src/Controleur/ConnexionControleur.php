<?php
namespace App\Controleur;

require_once __DIR__ . '/../config/base_de_donnees.php';
require_once __DIR__ . '/../src/Modele/UtilisateurModele.php';

use App\Modele\UtilisateurModele;
session_start();

class ConnexionControleur {
    private $modele;

    public function __construct() {
        $db = obtenirBaseDeDonnees();
        $this->modele = new UtilisateurModele($db);
    }

    public function connexion($email, $motDePasse) {
        $utilisateurId = $this->modele->verifierUtilisateur($email, $motDePasse);
        if ($utilisateurId) {
            $_SESSION['utilisateur_id'] = $utilisateurId;
            return ['success' => true];
        } else {
            return ['success' => false, 'message' => "Identifiants incorrects"];
        }
    }

    public function deconnexion() {
        session_destroy();
        header("Location: connexion.php");
    }
}
?>
