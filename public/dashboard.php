<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../middlewares/AuthMiddleware.php';

use App\Middlewares\AuthMiddleware;

error_log("🚀 Test de log depuis dashboard.php");

// ✅ Vérification du token JWT si la requête est faite via Fetch API
$utilisateur = AuthMiddleware::verifierAuthentification();

// 🔄 Si la requête vient du navigateur et pas d'un fetch API, on ne bloque pas
if (!$utilisateur && !isset($_SERVER['HTTP_AUTHORIZATION'])) {
    error_log("⚠️ Aucun header Authorization trouvé. Utilisation du localStorage côté client.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center">Bienvenue, <span id="nom-utilisateur">Utilisateur inconnu</span> !</h1>
    <p class="text-center">Gérez vos tâches efficacement.</p>

    <div class="d-flex justify-content-center gap-3">
    <a href="/todolist/src/Vue/listes.php" class="btn btn-primary">Voir mes listes</a>
    <a href="/todolist/src/Vue/creer.php" class="btn btn-success">Créer une nouvelle liste</a>
    <button id="btn-deconnexion" class="btn btn-danger">
        <i class="fas fa-sign-out-alt"></i> Déconnexion
    </button>
</div>

</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    console.log("🔍 Vérification du token JWT au chargement de la page...");

    const token = localStorage.getItem("token");
    const utilisateur = JSON.parse(localStorage.getItem("utilisateur"));

    if (!token || !utilisateur) {
        console.error("❌ Aucun utilisateur trouvé. Redirection vers connexion.");
        window.location.href = "/todolist/public/connexion.php";
        return;
    }

    console.log("👤 Utilisateur détecté :", utilisateur);
    document.getElementById("nom-utilisateur").textContent = utilisateur.email;

    document.getElementById("btn-deconnexion").addEventListener("click", function() {
        localStorage.removeItem("token");
        localStorage.removeItem("utilisateur");
        window.location.href = "connexion.php";
    });
});
</script>

</body>
</html>
