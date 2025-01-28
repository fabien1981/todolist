<?php
namespace App\Modele;

require_once __DIR__ . '/../../vendor/autoload.php';
use MongoDB\BSON\ObjectId;

class TacheModele {
    private $collection;

    public function __construct($db) {
        $this->collection = $db->taches; // Nom de la collection
    }

    public function ajouterTache($tache) {
        $this->collection->insertOne($tache);
    }

    public function obtenirTaches() {
        return $this->collection->find()->toArray();
    }

    public function mettreAJourTache($id, $tache) {
        $this->collection->updateOne(
            ['_id' => new ObjectId($id)],
            ['$set' => $tache]
        );
    }

    public function supprimerTache($id) {
        $this->collection->deleteOne(['_id' => new ObjectId($id)]);
    }
}
