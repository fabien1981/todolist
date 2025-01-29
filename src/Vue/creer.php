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
    document.querySelector("#formulaire-todolist").addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch("api.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(Object.fromEntries(formData))
        })
            .then((response) => response.json())
            .then((result) => {
                if (result.success) {
                    alert("Nouvelle liste créée !");
                    window.location.href = "listes.php";
                } else {
                    alert("Erreur lors de la création : " + result.message);
                }
            });
    });
</script>
</body>
</html>
