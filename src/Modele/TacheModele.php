<?php
namespace App\Modele;

require_once __DIR__ . '/../../vendor/autoload.php';

use MongoDB\BSON\ObjectId;
use MongoDB\BSON\UTCDateTime;

/** @var \MongoDB\Collection $this->collection */
class TacheModele {
    private $collection;

    public function __construct($db) {
        $this->collection = $db->taches; // Connexion à la collection
    }

    public function obtenirTaches() {
        try {
            $taches = $this->collection->find()->toArray();
            $resultat = [];
    
            foreach ($taches as $tache) {
                $resultat[] = [
                    '_id' => (string) $tache['_id'],
                    'titre' => $tache['titre'],
                    'description' => $tache['description'],
                    'priorite' => $tache['priorite'] ?? 'moyenne',
                    'statut' => $tache['statut'] ?? 'en attente',
                    'items' => $tache['items'] ?? [] // ✅ AJOUT DES SOUS-TÂCHES
                ];
            }
    
            return ['success' => true, 'data' => $resultat]; // ✅ AJOUT DE SUCCESS !
        } catch (\Exception $e) {
            return ['success' => false, 'message' => '❌ Erreur lors de la récupération des tâches : ' . $e->getMessage()];
        }
    }
    
    
    
    

    public function mettreAJourTache($id, $tache) {
        try {
            // ✅ Vérifier si l'ID est fourni
            if (!$id) {
                return ['success' => false, 'message' => '❌ ID manquant'];
            }
    
            // ✅ Vérifier si l'ID est valide (ObjectId MongoDB)
            try {
                $idMongo = new \MongoDB\BSON\ObjectId($id);
            } catch (\Exception $e) {
                return ['success' => false, 'message' => '❌ ID invalide'];
            }
            foreach ($tache['items'] as &$item) {
                if (!isset($item['_id'])) {
                    $item['_id'] = new ObjectId(); // ✅ Génération d'un ObjectId pour chaque sous-tâche
                }
            }
            // ✅ Préparer les données de mise à jour
            $miseAJour = [
                'titre' => $tache['titre'] ?? '',  // Si absent, valeur vide
                'description' => $tache['description'] ?? '',
                'items' => $tache['items'] ?? []  // Liste vide si "items" n'existe pas
            ];
    
            // ✅ Ajouter "priorite" uniquement si elle est définie
            if (!empty($tache['priorite'])) {
                $miseAJour['priorite'] = $tache['priorite'];
            }
    
            // ✅ Mettre à jour la tâche dans MongoDB
            $result = $this->collection->updateOne(
                ['_id' => $idMongo],  // 🔹 Utilisation de l'ObjectId
                ['$set' => $miseAJour]
            );
    
            // ✅ Vérifier si la mise à jour a bien été effectuée
            if ($result->getModifiedCount() > 0) {
                return ['success' => true, 'message' => '✅ Tâche mise à jour avec succès'];
            } else {
                return ['success' => false, 'message' => '❌ Aucune modification effectuée (tâche non trouvée ou identique)'];
            }
    
        } catch (\Exception $e) {
            return ['success' => false, 'message' => '❌ Erreur lors de la mise à jour : ' . $e->getMessage()];
        }
    }
    
    
    
    

    public function supprimerTache($id) {
        try {
            $result = $this->collection->deleteOne(['_id' => new \MongoDB\BSON\ObjectId($id)]);
            return $result;
        } catch (\Exception $e) {
            return ['success' => false, 'message' => '❌ Erreur MongoDB : ' . $e->getMessage()];
        }
    }
    
    public function obtenirTacheParId($id) {
        return $this->collection->findOne(['_id' => new ObjectId($id)]);
    }
    
    

    public function ajouterTache($tache) {
        try {
            $this->collection->insertOne([
                'titre' => $tache['titre'],
                'description' => $tache['description'],
                'priorite' => $tache['priorite'],
                'statut' => $tache['statut'],
                'date_creation' => new UTCDateTime(),
            ]);
            return ['success' => true, 'message' => '✅ Tâche ajoutée'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => '❌ Erreur MongoDB : ' . $e->getMessage()];
        }
    }
    
}
