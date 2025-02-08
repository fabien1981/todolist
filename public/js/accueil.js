document.addEventListener("DOMContentLoaded", function () {
    const contenuFormulaire = document.getElementById("contenu-formulaire");

    document.getElementById("tab-inscription").addEventListener("click", function (e) {
        e.preventDefault(); // Empêcher la redirection
        console.log("🛠️ Clic détecté sur 'Créer un compte' !");

        fetch("/todolist/public/api.php?action=inscription_vue")
            .then(response => response.text())
            .then(html => {
                console.log("✅ Réponse reçue !");
                contenuFormulaire.innerHTML = html;
            })
            .catch(error => console.error("❌ Erreur lors du chargement de inscription.php :", error));
    });
});
