document.addEventListener("DOMContentLoaded", function () {
    console.log("🔍 Script connexion.js chargé");

    document.querySelector("#formulaire-connexion").addEventListener("submit", function (e) {
        e.preventDefault();
        console.log("⏳ Tentative de connexion...");

        const formData = new FormData(this);
        const data = Object.fromEntries(formData);
        console.log("📤 Données envoyées :", JSON.stringify(data));

        fetch("/todolist/public/api.php?action=connexion", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            console.log("✅ Réponse API JSON :", result);

            if (result.success) {
                localStorage.setItem("token", result.token);
                console.log("🔍 Token stocké :", localStorage.getItem("token"));

                // 🔑 Stocker aussi les informations utilisateur
                const tokenParts = result.token.split(".");
                const payload = JSON.parse(atob(tokenParts[1]));
                localStorage.setItem("utilisateur", JSON.stringify(payload));

                console.log("👤 Utilisateur stocké :", payload);

                // ✅ Redirection vers dashboard
                setTimeout(() => {
                    console.log("🔄 Redirection vers dashboard...");
                    window.location.href = "/todolist/public/dashboard.php";
                }, 500);
            } else {
                alert("❌ Erreur : " + result.message);
            }
        })
        .catch(error => console.error("❌ Erreur lors de la connexion :", error));
    });
});
