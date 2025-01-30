document.addEventListener("DOMContentLoaded", function () {
    document.querySelector("#formulaire-connexion").addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        
        fetch("api.php?action=connexion", {
            method: "POST",
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                window.location.href = "listes.php";
            } else {
                alert(result.message);
            }
        })
        .catch(error => console.error("Erreur lors de la connexion :", error));
    });
});
