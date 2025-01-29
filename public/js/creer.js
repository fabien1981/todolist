document.addEventListener("DOMContentLoaded", function () {
    document.querySelector("#formulaire-todolist").addEventListener("submit", function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData);

        fetch("api.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data),
        })
            .then((response) => response.json())
            .then((result) => {
                if (result.success) {
                    alert("Nouvelle liste créée !");
                    window.location.href = "listes.php";
                } else {
                    alert("Erreur lors de la création : " + result.message);
                }
            })
            .catch((error) => console.error("Erreur lors de l'ajout :", error));
    });
});
