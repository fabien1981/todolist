<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listes de To-Do</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Vos To-Do Lists</h1>
    
    <!-- Ajout des boutons de tri et filtrage -->
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
        <!-- Les listes seront chargées dynamiquement ici -->
    </div>
</div>

<!-- Inclure le fichier JS -->
<script src="js/listes.js"></script>
</body>
</html>
