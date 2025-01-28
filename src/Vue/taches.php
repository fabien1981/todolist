<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-Do List</title>
    <!-- Lien vers Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Liste des tâches</h1>
    <!-- Formulaire d'ajout -->
    <form class="mb-4" id="formulaire-tache">
        <input type="hidden" name="action" value="ajouter">
        <div class="mb-3">
            <label for="titre" class="form-label">Titre</label>
            <input type="text" name="titre" id="titre" class="form-control" placeholder="Titre de la tâche" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3" placeholder="Description de la tâche" required></textarea>
        </div>
        <div class="mb-3">
            <label for="statut" class="form-label">Statut</label>
            <select name="statut" id="statut" class="form-select" required>
                <option value="en-cours">En cours</option>
                <option value="terminee">Terminée</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter une tâche</button>
    </form>

    <!-- Liste des tâches -->
    <ul class="list-group">
        <?php foreach ($taches as $tache): ?>
            <li class="list-group-item">
                <strong><?= htmlspecialchars($tache['titre']) ?></strong> - <?= htmlspecialchars($tache['description']) ?> (<?= htmlspecialchars($tache['statut']) ?>)
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<script src="js/application.js"></script>
</body>
</html>
