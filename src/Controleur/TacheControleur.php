<?php
namespace App\Controleur;

use App\Modele\TacheModele;

class TacheControleur {
    private $modele;

    public function __construct() {
        $db = obtenirBaseDeDonnees(); // Connexion à la base de données
        $this->modele = new TacheModele($db);
    }

    // Gère les requêtes POST (ajout de tâches)
    public function gererRequete() {
        $action = $_POST['action'] ?? '';

        if ($action === 'ajouter') {
            $tache = [
                'titre' => $_POST['titre'],
                'description' => $_POST['description'],
                'statut' => $_POST['statut'],
            ];
            try {
                $this->modele->ajouterTache($tache);
                echo json_encode(['success' => true]);
            } catch (\Exception $e) {
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            }
        }
    }

    // Récupère et affiche la liste des tâches
    public function afficherTaches() {
        try {
            $taches = $this->modele->obtenirTaches();
            echo json_encode($taches);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // Supprime une tâche
    public function supprimerTache($data) {
        if (!isset($data['id'])) {
            echo json_encode(['success' => false, 'message' => 'ID manquant']);
            return;
        }

        $id = $data['id'];

        try {
            $this->modele->supprimerTache($id);
            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // Met à jour une tâche
    public function mettreAJourTache($data) {
        if (!isset($data['id']) || !isset($data['titre']) || !isset($data['description']) || !isset($data['statut'])) {
            echo json_encode(['success' => false, 'message' => 'Données incomplètes']);
            return;
        }

        $id = $data['id'];
        $tache = [
            'titre' => $data['titre'],
            'description' => $data['description'],
            'statut' => $data['statut'],
        ];

        try {
            $this->modele->mettreAJourTache($id, $tache);
            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
