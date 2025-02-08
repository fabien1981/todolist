document.addEventListener("DOMContentLoaded", function () {
    console.log("🔍 Chargement de listes.js - Vérification du token JWT...");

    const token = localStorage.getItem("token");

    if (!token) {
        console.error("❌ Aucun token trouvé. Redirection vers connexion.");
        window.location.href = "/todolist/public/connexion.php";
        return;
    }

    chargerListes();

    // Vérification et ajout de l'événement pour le filtre de priorité
    const filtre = document.querySelector("#filtre-priorite");
    if (filtre) {
        filtre.addEventListener("change", chargerListes);
    }
});
// ✅ Fonction pour charger et afficher les tâches
function chargerListes() {
    console.log("📡 Chargement des tâches...");

    const token = localStorage.getItem("token");

    fetch("/todolist/public/api.php?action=taches", {
        method: "GET",
        headers: {
            "Authorization": `Bearer ${token}`,
            "Content-Type": "application/json"
        }
    })
    .then(response => response.json())
    .then(result => {
        console.log("✅ Réponse API - Tâches récupérées :", result);

        const container = document.querySelector("#liste-container");
        if (!container) {
            console.error("❌ L'élément #liste-container est introuvable !");
            return;
        }

        container.innerHTML = "";

        if (!result.success || !result.data || result.data.length === 0) {
            container.innerHTML = "<p class='text-center text-muted'>Aucune tâche trouvée.</p>";
            return;
        }

        const filtrePriorite = document.querySelector("#filtre-priorite")?.value || "toutes";

        result.data.forEach((tache) => {
            // ✅ Filtrage des tâches par priorité
            if (filtrePriorite !== "toutes" && tache.priorite !== filtrePriorite) {
                return;
            }

            const item = document.createElement("div");
            item.className = "list-group-item d-flex justify-content-between align-items-center";
            item.innerHTML = `
                <div>
                    <span class="ms-2 tache-item" data-id="${tache._id}" style="cursor: pointer;">
                        <strong>${tache.titre}</strong> - ${tache.description} 
                        <span class="badge bg-${getPriorityColor(tache.priorite)}">${tache.priorite}</span>
                    </span>
                </div>
                <div>
                    <button class="btn btn-danger btn-sm supprimer-liste" data-id="${tache._id}">🗑 Supprimer</button>
                </div>
            `;
            container.appendChild(item);
        });

        // ✅ Attacher les événements pour affichage et suppression
        document.querySelectorAll(".tache-item").forEach((item) => {
            item.addEventListener("click", function () {
                afficherTache(this.dataset.id);
            });
        });

        document.querySelectorAll(".supprimer-liste").forEach((btn) => {
            btn.addEventListener("click", function (e) {
                e.stopPropagation();
                supprimerListe(this.dataset.id);
            });
        });

        console.log("✅ Tâches affichées avec succès !");
    })
    .catch(error => console.error("❌ Erreur lors du chargement des listes :", error));
}

// ✅ Fonction pour attribuer une couleur à la priorité
function getPriorityColor(priorite) {
    switch (priorite) {
        case "haute": return "danger"; // Rouge
        case "moyenne": return "warning"; // Jaune
        case "basse": return "success"; // Vert
        default: return "secondary"; // Gris si inconnu
    }
}

// ✅ Fonction pour afficher une tâche dans une modale
function afficherTache(id) {
    console.log("📌 Tentative d'affichage de la tâche avec ID :", id);
    if (!id) {
        console.error("❌ Erreur : L'ID de la tâche est introuvable !");
        return;
    }


    console.log("📌 Chargement de la tâche avec ID :", id);

    const token = localStorage.getItem("token");

    fetch(`/todolist/public/api.php?action=obtenir_tache_et_items&id=${id}`, {
        method: "GET",
        headers: {
            "Authorization": `Bearer ${token}`,
            "Content-Type": "application/json"
        }
    })
    .then(response => response.json())
    .then(result => {
        console.log("🔍 Réponse API - Tâche récupérée :", result);

        if (!result.success || !result.data) {
            alert("❌ Impossible d'afficher la tâche.");
            return;
        }

        const tache = result.data;
        const sousTaches = tache.items || [];

        // Supprime une ancienne modale s'il en existe déjà une
        const existingModal = document.getElementById("modalTache");
        if (existingModal) existingModal.remove();

        let modalContent = `
            <div class="modal fade" id="modalTache" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Modifier la tâche</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                        </div>
                        <div class="modal-body">
                            <form id="formulaire-modification" data-id="${tache._id}">
                                <div class="mb-3">
                                    <label for="titre" class="form-label">Titre</label>
                                    <input type="text" class="form-control" id="titre" value="${tache.titre}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" required>${tache.description}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Sous-tâches</label>
                                    <ul id="sous-taches-container" class="list-group">
                                        ${sousTaches.length > 0 ? sousTaches.map(item => `
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <input type="checkbox" class="form-check-input me-2 check-sous-tache" data-id="${item.id}" ${item.fait ? 'checked' : ''}>
                                                    <input type="text" class="form-control me-2 sous-tache-titre" value="${item.titre}">
                                                </div>
                                                <button type="button" class="btn btn-danger btn-sm supprimer-sous-tache">🗑</button>
                                            </li>
                                        `).join('') : `<p class="text-muted">Aucune sous-tâche</p>`}
                                    </ul>
                                    <button type="button" class="btn btn-success mt-2" id="ajouter-sous-tache">➕ Ajouter</button>
                                </div>
                                <button type="submit" class="btn btn-primary">Enregistrer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML("beforeend", modalContent);
        const modal = new bootstrap.Modal(document.getElementById("modalTache"));
        modal.show();

        // ✅ Attacher l'événement d'ajout de sous-tâche
        document.getElementById("ajouter-sous-tache").addEventListener("click", function () {
            const container = document.getElementById("sous-taches-container");
            if (!container) {
                console.error("❌ Le conteneur des sous-tâches est introuvable !");
                return;
            }

            const newItemHTML = `
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <input type="checkbox" class="form-check-input me-2 check-sous-tache">
                        <input type="text" class="form-control me-2 sous-tache-titre" placeholder="Nouvelle sous-tâche">
                    </div>
                    <button type="button" class="btn btn-danger btn-sm supprimer-sous-tache">🗑</button>
                </li>
            `;

            container.insertAdjacentHTML("beforeend", newItemHTML);
            console.log("✅ Sous-tâche ajoutée !");
        });

        // ✅ Attacher l'événement de suppression des sous-tâches
        document.getElementById("sous-taches-container").addEventListener("click", function (event) {
            if (event.target.classList.contains("supprimer-sous-tache")) {
                event.target.closest("li").remove();
                console.log("✅ Sous-tâche supprimée !");
            }
        });

    })
    .catch(error => console.error("❌ Erreur lors de l'affichage de la tâche :", error));
}


// ✅ Fonction pour récupérer les sous-tâches du DOM avant l'envoi
function recupererSousTaches() {
    let sousTaches = [];

    document.querySelectorAll("#sous-taches-container .list-group-item").forEach((item) => {
        const titreSousTache = item.querySelector(".sous-tache-titre")?.value.trim();
        const fait = item.querySelector(".check-sous-tache")?.checked;

        if (titreSousTache) {
            sousTaches.push({ titre: titreSousTache, fait: fait });
        }
    });

    console.log("🔍 Sous-tâches récupérées dans recupererSousTaches() :", sousTaches);
    return sousTaches; // ✅ Retourne bien la liste des sous-tâches
}

// ✅ Fonction pour modifier une tâche (y compris les sous-tâches)
function modifierTache(id) {
    if (!id) {
        console.error("❌ ID manquant lors de la modification !");
        return;
    }
    console.log("✏️ Modification de la tâche avec ID :", id);

    const titre = document.getElementById("titre")?.value.trim();
    const description = document.getElementById("description")?.value.trim();
    const priorite = document.getElementById("priorite")?.value || "moyenne"; // ✅ Priorité par défaut

    if (!titre || !description) {
        alert("❌ Titre et description sont obligatoires !");
        return;
    }

    // ✅ Récupération correcte des sous-tâches AVANT de les utiliser
    const sousTaches = recupererSousTaches(); 

    // ✅ Vérification console avant l'envoi
    console.log("📌 Vérification avant envoi - ID :", id);
    console.log("🔍 Sous-tâches récupérées :", sousTaches);

    // ✅ Création de l'objet à envoyer
    const data = {
        id: id,
        titre: titre,
        description: description,
        priorite: priorite, // Ajout de la priorité
        items: sousTaches // ✅ Liste des sous-tâches bien définie
    };

    console.log("📤 Données envoyées :", JSON.stringify(data, null, 2));

    // ✅ Envoi des données à l'API
    fetch("/todolist/public/api.php?action=modifier_todolist", {
        method: "PUT",
        headers: {
            "Authorization": `Bearer ${localStorage.getItem("token")}`,
            "Content-Type": "application/json"
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json()) 
    .then(result => {
        console.log("🔍 Réponse API :", result);

        if (result.success) {
            alert("✅ Modifications enregistrées !");
            
            // ✅ Fermer la modale après l'enregistrement
            const modal = bootstrap.Modal.getInstance(document.getElementById("modalTache"));
            if (modal) {
                modal.hide(); // Ferme la modale
            }

            // ✅ Recharger la liste des tâches
            chargerListes(); // ✅ Recharge les listes

            // ✅ Optionnel : réinitialiser ou rafraîchir l'interface pour éviter la page grisée
            document.body.classList.remove("modal-open");
            document.querySelector(".modal-backdrop").remove();
        } else {
            alert("❌ Erreur lors de l'enregistrement : " + result.message);
        }
    })
    .catch(error => console.error("❌ Erreur lors de l'enregistrement :", error));
}

// ✅ Événement de soumission du formulaire APRÈS affichage de la modale
document.addEventListener("submit", function (event) {
    if (event.target && event.target.id === "formulaire-modification") {
        event.preventDefault();
        
        const id = event.target.getAttribute("data-id");
        console.log("📌 ID récupéré du formulaire :", id);

        if (!id) {
            console.error("❌ ID manquant lors de l'appel à modifierTache !");
            return;
        }

        modifierTache(id); // ✅ Appel correct de la fonction
    }
});



// ✅ Fonction pour supprimer une tâche
function supprimerListe(id) {
    if (!confirm("Voulez-vous vraiment supprimer cette tâche ?")) return;

    const token = localStorage.getItem("token");

    fetch("/todolist/public/api.php?action=supprimer_todolist", {
        method: "DELETE",
        headers: {
            "Authorization": `Bearer ${token}`,
            "Content-Type": "application/json"
        },
        body: JSON.stringify({ id })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            alert("✅ Tâche supprimée !");
            chargerListes();
        } else {
            alert("❌ Erreur lors de la suppression : " + result.message);
        }
    })
    .catch(error => console.error("❌ Erreur lors de la suppression :", error));
}
