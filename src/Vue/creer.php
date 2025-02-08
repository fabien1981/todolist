<?php
require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../middlewares/AuthMiddleware.php';



use App\Middlewares\AuthMiddleware;

error_log("🚀 Test de log depuis creer.php");

$utilisateur = AuthMiddleware::verifierAuthentification();

if (!$utilisateur && !isset($_SERVER['HTTP_AUTHORIZATION'])) {
    error_log("⚠️ Aucun header Authorization trouvé. Utilisation du localStorage côté client.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer une To-Do List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">Créer une nouvelle To-Do List</h1>
    <form id="formulaire-todolist">
        <div class="mb-3">
            <label for="titre" class="form-label">Titre de la liste</label>
            <input type="text" id="titre" name="titre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-success">Créer</button>
    </form>
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

    document.querySelector("#formulaire-todolist").addEventListener("submit", function (e) {
        e.preventDefault();

        const data = {
            titre: document.getElementById("titre").value,
            description: document.getElementById("description").value,
            action: "ajouter_todolist"
        };

        fetch("/todolist/public/api.php?action=ajouter_todolist", {
            method: "POST",
            headers: {
                "Authorization": `Bearer ${token}`,
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                alert("✅ Liste créée avec succès !");
                window.location.href = "/todolist/public/listes.php";
            } else {
                alert("❌ Erreur : " + result.message);
            }
        })
        .catch(error => console.error("❌ Erreur :", error));
    });
});
</script>

</body>
</html>
