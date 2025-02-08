<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use App\Middlewares\AuthMiddleware;

// ✅ Vérification du token JWT
$utilisateur = AuthMiddleware::verifierAuthentification();

if ($utilisateur) {
    error_log("🔄 Redirection vers dashboard.php car utilisateur connecté.");
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil - To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <h1 class="text-center">Bienvenue sur votre To-Do List</h1>
        
        <div class="d-flex justify-content-center mt-4">
            <a href="connexion.php" class="btn btn-primary me-3">Se connecter</a>
            <a href="inscription.php" class="btn btn-success">Créer un compte</a>
        </div>
    </div>

    <script>
        // ✅ Vérifie si un token JWT est stocké dans le navigateur
        const token = localStorage.getItem("token");
        if (token) {
            console.log("🔍 Token détecté, mais redirection désactivée pour test :", token);
        }
    </script>

</body>
</html>
