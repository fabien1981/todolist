<?php
namespace App\Controleur;

use App\Modele\TacheModele;

class TacheControleur {
    private $modele;

    public function __construct() {
        $db = obtenirBaseDeDonnees(); // Connexion à la base de données
        $this->modele = new TacheModele($db);
    }

    // Gère les requêtes POST (ajout/modification/suppression)
    public function gererRequete() {
        $action = $_POST['action'] ?? '';

        if ($action === 'ajouter') {
            $tache = [
                'titre' => $_POST['titre'],
                'description' => $_POST['description'],
                'statut' => $_POST['statut'],
            ];
            $this->modele->ajouterTache($tache);
            echo json_encode(['succes' => true]);
        }
    }

    // Affiche la liste des tâches (requêtes GET)
    public function afficherTaches() {
        $taches = $this->modele->obtenirTaches();
        include __DIR__ . '/../Vue/taches.php';
    }
}
