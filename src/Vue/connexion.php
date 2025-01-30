<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center mb-4">Connexion</h1>
    <form id="formulaire-connexion">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" placeholder="Email" required>
        </div>
        <div class="mb-3">
            <label for="motDePasse" class="form-label">Mot de passe</label>
            <input type="password" id="motDePasse" name="motDePasse" class="form-control" placeholder="Mot de passe" required>
        </div>
        <button type="submit" class="btn btn-primary">Connexion</button>
    </form>
</div>

<!-- Inclure le fichier JS -->
<script src="js/connexion.js"></script>
</body>
</html>
