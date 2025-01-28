<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <!-- Lien vers Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Liste des tâches</h1>
    <!-- Formulaire d'ajout -->
    <form class="mb-4">
        <div class="mb-3">
            <label for="titre" class="form-label">Titre</label>
            <input type="text" id="titre" class="form-control" placeholder="Titre de la tâche">
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" class="form-control" rows="3" placeholder="Description de la tâche"></textarea>
        </div>
        <div class="mb-3">
            <label for="statut" class="form-label">Statut</label>
            <select id="statut" class="form-select">
                <option value="en-cours">En cours</option>
                <option value="terminee">Terminée</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter une tâche</button>
    </form>
    <!-- Liste des tâches -->
    <ul class="list-group">
        <li class="list-group-item">Tâche 1 : Faire les courses</li>
        <li class="list-group-item">Tâche 2 : Ranger la maison</li>
        <li class="list-group-item">Tâche 3 : Réviser le projet</li>
    </ul>
</div>

</body>
</html>
