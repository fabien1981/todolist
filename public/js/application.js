document.addEventListener("DOMContentLoaded", function () {
    // Sélectionne le formulaire et gère l'ajout d'une tâche
    document.querySelector("#formulaire-tache").addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        data.action = "ajouter_todolist"; // ✅ Spécification de l'action

        fetch("/todolist/public/api.php", { // ✅ Chemin absolu
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data),
        })
        .then((response) => response.json())
        .then((result) => {
            console.log("📩 Réponse API :", result);
            if (result.success) {
                alert("✅ Nouvelle liste créée !");
                chargerTaches(); // ✅ Recharge la liste après ajout
                this.reset(); // ✅ Réinitialise le formulaire
            } else {
                alert("❌ Erreur lors de la création : " + result.message);
            }
        })
        .catch((error) => console.error("❌ Erreur lors de l'ajout :", error));
    });

    // Charger les tâches au chargement de la page
    chargerTaches();
});

// ✅ Fonction pour charger toutes les tâches (GET)
function chargerTaches() {
    fetch("/todolist/public/api.php?action=taches", { // ✅ Chemin absolu et action correcte
        method: "GET",
    })
    .then((response) => response.json())
    .then((taches) => {
        const liste = document.querySelector(".list-group");
        liste.innerHTML = ""; // Réinitialiser la liste
        taches.forEach((tache) => {
            const element = document.createElement("li");
            element.classList.add("list-group-item");
            element.innerHTML = `
                <strong>${tache.titre}</strong> - ${tache.description} (${tache.statut})
                <button class="btn btn-sm btn-danger float-end supprimer-tache" data-id="${tache._id}">Supprimer</button>
            `;
            liste.appendChild(element);
        });

        // Attacher les événements de suppression après le chargement des tâches
        document.querySelectorAll(".supprimer-tache").forEach((bouton) => {
            bouton.addEventListener("click", function () {
                supprimerTache(this.dataset.id);
            });
        });
    })
    .catch((error) => console.error("❌ Erreur lors du chargement des tâches :", error));
}

// ✅ Fonction pour supprimer une tâche (DELETE)
function supprimerTache(id) {
    fetch("/todolist/public/api.php?action=supprimer_tache", { // ✅ Action spécifique pour la suppression
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id }),
    })
    .then((response) => response.json())
    .then((result) => {
        if (result.success) {
            alert("✅ Tâche supprimée !");
            chargerTaches(); // ✅ Recharge la liste après suppression
        } else {
            alert("❌ Erreur lors de la suppression de la tâche.");
        }
    })
    .catch((error) => console.error("❌ Erreur lors de la suppression :", error));
}
