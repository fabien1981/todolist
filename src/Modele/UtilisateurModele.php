<?php
namespace App\Modele;

require_once __DIR__ . '/../../vendor/autoload.php';


class UtilisateurModele {
    private $collection;

    public function __construct($db) {
        try {
            if (!$db instanceof \MongoDB\Database) {
                throw new \Exception("Erreur : La base de données MongoDB est invalide.");
            }
            $this->collection = $db->utilisateurs; // Connexion à la collection utilisateurs
        } catch (\Exception $e) {
            error_log("🚨 Erreur lors de la connexion MongoDB : " . $e->getMessage());
            throw $e;
        }
    }

    // ✅ MÉTHODE POUR CRÉER UN UTILISATEUR
    public function creerUtilisateur($email, $motDePasse) {
        try {
            // Vérifier si l'utilisateur existe déjà
            if ($this->collection->findOne(['email' => $email])) {
                return ['success' => false, 'message' => '❌ Cet email est déjà utilisé.'];
            }

            // Hachage du mot de passe
            $hash = password_hash($motDePasse, PASSWORD_BCRYPT);

            // Insérer l'utilisateur avec date de création
            $this->collection->insertOne([
                'email' => $email,
                'motDePasse' => $hash,
                'date_creation' => new \MongoDB\BSON\UTCDateTime() // Format MongoDB
            ]);

            return ['success' => true, 'message' => '✅ Inscription réussie !'];
        } catch (\Exception $e) {
            error_log("🚨 Erreur lors de l'inscription : " . $e->getMessage());
            return ['success' => false, 'message' => '❌ Erreur lors de l\'inscription : ' . $e->getMessage()];
        }
    }

    // ✅ MÉTHODE POUR VÉRIFIER L'EXISTENCE D'UN EMAIL
    public function verifierEmail($email) {
        return (bool) $this->collection->findOne(['email' => $email]);
    }

    public function emailExiste($email) {
        return $this->collection->findOne(['email' => $email]) ? true : false;
    }

    // ✅ MÉTHODE POUR TROUVER UN UTILISATEUR PAR EMAIL
    public function trouverParEmail($email) {
        try {
            $utilisateur = $this->collection->findOne(['email' => $email]);

            if ($utilisateur) {
                return [
                    '_id' => (string) $utilisateur['_id'],
                    'email' => $utilisateur['email'],
                    'motDePasse' => $utilisateur['motDePasse'], // Mot de passe haché
                ];
            }

            return null; // Aucun utilisateur trouvé
        } catch (\Exception $e) {
            error_log("🚨 Erreur lors de la récupération de l'utilisateur : " . $e->getMessage());
            return null;
        }
    }

    // ✅ MÉTHODE POUR VÉRIFIER L'UTILISATEUR
    public function verifierUtilisateur($email, $motDePasse) {
        try {
            $utilisateur = $this->trouverParEmail($email);

            if ($utilisateur && password_verify($motDePasse, $utilisateur['motDePasse'])) {
                return ['success' => true, 'utilisateurId' => (string) $utilisateur['_id']];
            }

            return ['success' => false, 'message' => '❌ Identifiants incorrects.'];
        } catch (\Exception $e) {
            error_log("🚨 Erreur lors de la connexion : " . $e->getMessage());
            return ['success' => false, 'message' => '❌ Erreur de connexion : ' . $e->getMessage()];
        }
    }
}
