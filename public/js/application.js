document.querySelector("#formulaire-tache").addEventListener("submit", function (e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch("index.php", {
        method: "POST",
        body: formData,
    })
        .then((response) => response.json())
        .then((result) => {
            if (result.succes) {
                alert("Tâche ajoutée !");
                location.reload();
            }
        });
});
