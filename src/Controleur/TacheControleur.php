<?php

namespace App\Controleur;

require_once realpath(__DIR__ . '/../../config/base_de_donnees.php');
require_once realpath(__DIR__ . '/../../src/Modele/TacheModele.php');

use App\Modele\TacheModele;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;



class TacheControleur {
    
    private $modele;

    public function __construct() {
        $db = obtenirBaseDeDonnees(); // Connexion à la base de données
        $this->modele = new TacheModele($db);
    }

    // ✅ Gère les requêtes POST / PUT envoyées en JSON
    public function gererRequete() {
        $donnees = json_decode(file_get_contents("php://input"), true);

        if (!isset($donnees['action'])) {
            echo json_encode(['success' => false, 'message' => '❌ Action non spécifiée']);
            return;
        }

        try {
            switch ($donnees['action']) {
                case 'ajouter':
                    echo json_encode($this->ajouterTache($donnees));
                    break;
                case 'supprimer':
                    echo json_encode($this->supprimerTache($donnees['id'] ?? null));
                    break;
                case 'mettreAJour':
                    echo json_encode($this->mettreAJourTache($donnees['id'] ?? null, $donnees));
                    break;
                default:
                    echo json_encode(['success' => false, 'message' => '❌ Action inconnue']);
                    break;
            }
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => '❌ Erreur : ' . $e->getMessage()]);
        }
    }

    // ✅ Obtenir toutes les tâches
    public function obtenirTaches() {
        return $this->modele->obtenirTaches();
    }
    

    public function mettreAJourStatut($id, $statut) {
        try {
            $this->modele->mettreAJourTache(new ObjectId($id), ['statut' => $statut]);
            return ['success' => true, 'message' => '✅ Statut mis à jour'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => "❌ Erreur : " . $e->getMessage()];
        }
    }
    

    // ✅ Ajouter une nouvelle tâche
    public function ajouterTache($donnees) {
        if (empty($donnees['titre']) || empty($donnees['description']) || empty($donnees['priorite'])) {
            return ['success' => false, 'message' => '❌ Données incomplètes'];
        }

        try {
            $tache = [
                'titre' => trim($donnees['titre']),
                'description' => trim($donnees['description']),
                'priorite' => $donnees['priorite'],
                'statut' => 'en attente',
                'date_creation' => new UTCDateTime(),
                'items' => [] // Initialisation vide des sous-tâches
            ];

            $this->modele->ajouterTache($tache);
            return ['success' => true, 'message' => '✅ Tâche ajoutée avec succès'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => '❌ Erreur : ' . $e->getMessage()];
        }
    }

    // ✅ Mettre à jour une tâche (y compris les sous-tâches)
    public function mettreAJourTache($id, $donnees) {
   
        if (!$id || empty($donnees['titre']) || empty($donnees['description'])) {
            return ['success' => false, 'message' => '❌ Données incomplètes'];
        }

        try {
            $miseAJour = [
                'titre' => $donnees['titre'],
                'description' => $donnees['description'],
                'priorite' => $donnees['priorite'] ?? null,
                'items' => $donnees['items'] ?? [] // Mise à jour des sous-tâches
            ];

            $this->modele->mettreAJourTache($id, $miseAJour);
            return ['success' => true, 'message' => '✅ Tâche mise à jour avec succès'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => '❌ Erreur : ' . $e->getMessage()];
        }
    }

    // ✅ Supprimer une tâche
    public function supprimerTache($id) {
        if (!$id) {
            return ['success' => false, 'message' => '❌ ID manquant'];
        }
    
        try {
            $idMongo = new ObjectId($id); // ✅ Conversion en ObjectId
    
            $deleteResult = $this->modele->supprimerTache($idMongo); // ✅ Utilisation de l'ObjectId
    
            if ($deleteResult->getDeletedCount() > 0) {
                return ['success' => true, 'message' => '✅ Tâche supprimée avec succès'];
            } else {
                return ['success' => false, 'message' => '❌ Aucune tâche trouvée avec cet ID'];
            }
        } catch (\Exception $e) {
            return ['success' => false, 'message' => '❌ Erreur : ' . $e->getMessage()];
        }
    }
    

    // ✅ Obtenir une tâche et ses sous-tâches
    public function obtenirTacheEtItems($id) {
        if (!$id) {
            return ['success' => false, 'message' => '❌ ID manquant'];
        }
    
        try {
            $idMongo = new ObjectId($id); // ✅ Conversion en ObjectId
    
            $tache = $this->modele->obtenirTacheParId($idMongo); // ✅ Utilisation de l'ObjectId
    
            if (!$tache) {
                return ['success' => false, 'message' => '❌ Tâche introuvable'];
            }
    
            $tache['_id'] = (string) $tache['_id']; // Convertir en string pour l'affichage
            return ['success' => true, 'data' => $tache];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => '❌ Erreur : ' . $e->getMessage()];
        }
    }
    
}
