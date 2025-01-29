document.addEventListener("DOMContentLoaded", function () {
    chargerListes();

    // Gestion du filtre
    document.querySelector("#filtre-priorite").addEventListener("change", chargerListes);
});

// Fonction pour charger les listes existantes
function chargerListes() {
    fetch("api.php", { method: "GET" })
        .then((response) => response.json())
        .then((listes) => {
            const container = document.querySelector("#liste-container");
            container.innerHTML = ""; // Réinitialiser la liste

            // Récupérer la valeur du filtre
            const filtrePriorite = document.querySelector("#filtre-priorite").value;

            listes.forEach((liste) => {
                if (filtrePriorite !== "toutes" && liste.priorite !== filtrePriorite) {
                    return; // Ne pas afficher si la priorité ne correspond pas au filtre
                }

                const item = document.createElement("div");
                item.className = "list-group-item";
                item.innerHTML = `
                    <strong>${liste.titre}</strong> - ${liste.description} <br>
                    <span class="badge bg-${getPriorityColor(liste.priorite)}">${liste.priorite}</span>
                    <button class="btn btn-danger btn-sm float-end supprimer-liste" data-id="${liste._id}">Supprimer</button>
                `;
                container.appendChild(item);
            });

            // Ajouter les événements pour supprimer
            document.querySelectorAll(".supprimer-liste").forEach((btn) => {
                btn.addEventListener("click", function () {
                    supprimerListe(this.dataset.id);
                });
            });
        })
        .catch((error) => console.error("Erreur lors du chargement des listes :", error));
}

// Fonction pour supprimer une liste
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
        })
        .catch((error) => console.error("Erreur lors de la suppression :", error));
}

// Fonction pour attribuer une couleur à la priorité
function getPriorityColor(priorite) {
    switch (priorite) {
        case "haute": return "danger";
        case "basse": return "success";
        default: return "warning";
    }
}
