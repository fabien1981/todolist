// Sélectionne le formulaire et gère l'ajout d'une tâche
document.querySelector("#formulaire-tache").addEventListener("submit", function (e) {
    e.preventDefault();

    // Récupérer les données du formulaire
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    // Appel API pour ajouter une tâche (POST)
    fetch("api.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(data),
    })
        .then((response) => response.json())
        .then((result) => {
            if (result.success) {
                alert("Tâche ajoutée !");
                chargerTaches(); // Recharger la liste des tâches
                this.reset(); // Réinitialiser le formulaire
            } else {
                alert("Erreur lors de l'ajout de la tâche.");
            }
        })
        .catch((error) => console.error("Erreur :", error));
});

// Fonction pour charger toutes les tâches (GET)
function chargerTaches() {
    fetch("api.php", {
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
        .catch((error) => console.error("Erreur lors du chargement des tâches :", error));
}

// Fonction pour supprimer une tâche (DELETE)
function supprimerTache(id) {
    fetch("api.php", {
        method: "DELETE",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify({ id }),
    })
        .then((response) => response.json())
        .then((result) => {
            if (result.success) {
                alert("Tâche supprimée !");
                chargerTaches(); // Recharger la liste des tâches
            } else {
                alert("Erreur lors de la suppression de la tâche.");
            }
        })
        .catch((error) => console.error("Erreur :", error));
}

// Charger les tâches au chargement de la page
document.addEventListener("DOMContentLoaded", function () {
    chargerTaches();
});
