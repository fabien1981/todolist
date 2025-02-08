<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1 class="text-center">Créer un compte</h1>

    <form id="formulaire-inscription">
        <!-- Champ Email -->
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" id="email" name="email" class="form-control" required>
            <small id="email-feedback" class="text-danger"></small>
        </div>

        <!-- Champ Mot de passe -->
        <div class="mb-3">
            <label for="motDePasse" class="form-label">Mot de passe</label>
            <input type="password" id="motDePasse" name="motDePasse" class="form-control" required>
            <small id="password-feedback" class="text-danger"></small>
        </div>

        <button type="submit" class="btn btn-success">Créer un compte</button>
    </form>
</div>

<!-- JavaScript -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("motDePasse");
    const emailFeedback = document.getElementById("email-feedback");
    const passwordFeedback = document.getElementById("password-feedback");
    const formulaire = document.getElementById("formulaire-inscription");

    // ✅ Vérification en temps réel si l'email est déjà utilisé
    emailInput.addEventListener("input", function () {
        const email = emailInput.value.trim();
        if (email.length < 5) return; // Évite les requêtes inutiles

        fetch(`/todolist/public/api.php?action=verifier_email&email=${encodeURIComponent(email)}`)
            .then(response => response.json())
            .then(data => {
                emailFeedback.textContent = data.existe ? "❌ Cet email est déjà utilisé." : "";
            })
            .catch(error => console.error("❌ Erreur de vérification email :", error));
    });

    // ✅ Vérification de la force du mot de passe
    passwordInput.addEventListener("input", function () {
        const password = passwordInput.value;
        const regexMotDePasse = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

        if (!regexMotDePasse.test(password)) {
            passwordFeedback.textContent = "Le mot de passe doit contenir : 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.";
        } else {
            passwordFeedback.textContent = "";
        }
    });

    // ✅ Gestion de la soumission du formulaire
    formulaire.addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        const data = Object.fromEntries(formData);

        console.log("📤 Données envoyées :", data);

        fetch("/todolist/public/api.php?action=inscription", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
    console.log("📩 Réponse API :", result);
    if (result.success) {
    formulaire.innerHTML = `<p class="alert alert-success">✅ Inscription réussie ! Redirection vers l'accueil...</p>`;
    
    setTimeout(() => {
        window.location.href = "/todolist/public/";
    }, 3000);
} else {
    document.getElementById("email-feedback").textContent = result.message;
}

        })
        .catch(error => console.error("❌ Erreur lors de l'inscription :", error));
    });
});
</script>
</body>
</html>
