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
    <div id="liste-container" class="list-group">
        <!-- Les listes seront chargées dynamiquement ici -->
    </div>
</div>
<script>
    // Charger les listes existantes
    function chargerListes() {
        fetch("api.php", { method: "GET" })
            .then((response) => response.json())
            .then((listes) => {
                const container = document.querySelector("#liste-container");
                container.innerHTML = ""; // Réinitialiser la liste
                listes.forEach((liste) => {
                    const item = document.createElement("a");
                    item.href = "#";
                    item.className = "list-group-item list-group-item-action";
                    item.innerHTML = `
                        <strong>${liste.titre}</strong>
                        <p>${liste.description}</p>
                        <button class="btn btn-danger btn-sm supprimer-liste" data-id="${liste._id}">Supprimer</button>
                    `;
                    container.appendChild(item);
                });

                // Ajouter les événements pour supprimer
                document.querySelectorAll(".supprimer-liste").forEach((btn) => {
                    btn.addEventListener("click", function () {
                        const id = this.dataset.id;
                        supprimerListe(id);
                    });
                });
            });
    }

    // Supprimer une liste
    function supprimerListe(id) {
        fetch("api.php", {
            method: "DELETE",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id }),
        })
            .then((response) => response.json())
            .then((result) => {
                if (result.success) {
                    alert("Liste supprimée !");
                    chargerListes();
                } else {
                    alert("Erreur lors de la suppression : " + result.message);
                }
            });
    }

    document.addEventListener("DOMContentLoaded", chargerListes);
</script>
</body>
</html>
