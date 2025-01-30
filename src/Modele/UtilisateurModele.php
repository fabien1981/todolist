<?php
namespace App\Modele;

require_once __DIR__ . '/../../vendor/autoload.php';

use MongoDB\BSON\ObjectId;
use MongoDB\Client;

class UtilisateurModele {
    private $collection;

    public function __construct($db) {
        $this->collection = $db->utilisateurs;
    }

    public function creerUtilisateur($email, $motDePasse) {
        // Vérifier si l'utilisateur existe déjà
        if ($this->collection->findOne(['email' => $email])) {
            return false;
        }

        $hash = password_hash($motDePasse, PASSWORD_BCRYPT);
        $this->collection->insertOne([
            'email' => $email,
            'motDePasse' => $hash
        ]);
        return true;
    }

    public function verifierUtilisateur($email, $motDePasse) {
        $utilisateur = $this->collection->findOne(['email' => $email]);
        if ($utilisateur && password_verify($motDePasse, $utilisateur['motDePasse'])) {
            return (string) $utilisateur['_id'];
        }
        return false;
    }
}
?>
