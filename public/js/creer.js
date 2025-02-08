document.addEventListener("DOMContentLoaded", function () {
    console.log("🔍 Vérification du token JWT...");

    const token = localStorage.getItem("token");
    if (!token) {
        console.error("❌ Aucun token trouvé. Redirection vers connexion.");
        window.location.href = "/todolist/public/connexion.php";
        return;
    }

    document.querySelector("#formulaire-todolist").addEventListener("submit", function (e) {
        e.preventDefault();

        const data = {
            titre: document.getElementById("titre").value,
            description: document.getElementById("description").value,
            action: "ajouter_todolist"
        };

        fetch("/todolist/public/api.php?action=ajouter_todolist", {
            method: "POST",
            headers: {
                "Authorization": `Bearer ${token}`,
                "Content-Type": "application/json"
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                window.location.href = "/todolist/public/listes.php";
            } else {
                alert("❌ Erreur : " + result.message);
            }
        })
        .catch(error => console.error("❌ Erreur :", error));
    });
});
