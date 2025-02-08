<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../middlewares/AuthMiddleware.php';

use App\Middlewares\AuthMiddleware;

error_log("🚀 Test de log depuis listes.php");

// ✅ Vérification du token JWT
$utilisateur = AuthMiddleware::verifierAuthentification();
error_log("👤 Utilisateur JWT : " . json_encode($utilisateur));

// ❌ Ne pas rediriger ici, JavaScript s'en occupe
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listes de To-Do</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>

<div class="container mt-5">
    <a href="/todolist/public/dashboard.php" class="btn btn-primary mb-3">
        <i class="fas fa-home"></i> Retour au tableau de bord
    </a>

    <h1 class="text-center mb-4">Vos To-Do Lists</h1>

    <div class="mb-3">
        <label for="filtre-priorite" class="form-label">Filtrer par priorité :</label>
        <select id="filtre-priorite" class="form-select">
            <option value="toutes">Toutes</option>
            <option value="basse">Basse</option>
            <option value="moyenne">Moyenne</option>
            <option value="haute">Haute</option>
        </select>
    </div>

    <div id="liste-container" class="list-group">
        <p class="text-center text-muted">Chargement en cours...</p>
    </div>
</div>

<!-- Charger Bootstrap JS (obligatoire pour modales) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Charger le script JS pour la gestion des listes -->
<script src="/todolist/public/js/listes.js"></script>

</body>
</html>
