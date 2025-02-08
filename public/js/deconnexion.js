document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("btn-deconnexion").addEventListener("click", function () {
        fetch("api.php?action=deconnexion", { method: "POST" })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    console.log("✅ Déconnexion réussie, redirection...");
                    window.location.href = result.redirect || "/todolist/public/index.php"; // Vérifie si "redirect" est défini
                } else {
                    alert("❌ Erreur lors de la déconnexion : " + result.message);
                }
            })
            .catch(error => console.error("❌ Erreur lors de la déconnexion :", error));
    });
});
