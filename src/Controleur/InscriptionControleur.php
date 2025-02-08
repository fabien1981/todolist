<?php
namespace App\Controleur;

require_once __DIR__ . '/../../config/base_de_donnees.php';

require_once __DIR__ . '/../../src/Modele/UtilisateurModele.php';

use App\Modele\UtilisateurModele;

class InscriptionControleur {
    private $modele;

    public function __construct() {
        $db = obtenirBaseDeDonnees();
        $this->modele = new UtilisateurModele($db);
    }

    public function verifierEmail($email) {
        return $this->modele->verifierEmail($email);
    }
    

    public function inscription($email, $motDePasse) {
        if (empty($email) || empty($motDePasse)) {
            return ['success' => false, 'message' => '❌ Tous les champs sont obligatoires'];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'message' => '❌ Email invalide'];
        }

        // ✅ Vérifier si l'email existe déjà en base
        if ($this->modele->emailExiste($email)) {
            return ['success' => false, 'message' => '❌ Cet email est déjà utilisé'];
        }

        if ($this->modele->creerUtilisateur($email, $motDePasse)) {
            return ['success' => true, 'message' => '✅ Inscription réussie'];
        } else {
            return ['success' => false, 'message' => '❌ Une erreur est survenue lors de l’inscription'];
        }
    }
}
?>
