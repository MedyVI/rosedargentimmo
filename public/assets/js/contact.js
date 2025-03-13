// Validation côté utilisateur

document.addEventListener("DOMContentLoaded", function () {
    const form = document.querySelector(".contact-form");

    form.addEventListener("submit", function (event) {
        let isValid = true;
        let errorMessage = "";

        // Récupération des champs
        const name = document.getElementById("name");
        const email = document.getElementById("email");
        const message = document.getElementById("message");

        // Vérification du nom
        if (name.value.trim() === "") {
            errorMessage += "Le champ Nom et Prénom est requis.\n";
            isValid = false;
        }

        // Vérification de l'email (format correct)
        const emailRegex = /^[^\\s@]+@[^\\s@]+\\.[^\\s@]+$/;
        if (!emailRegex.test(email.value.trim())) {
            errorMessage += "Veuillez entrer une adresse email valide.\n";
            isValid = false;
        }

        // Vérification du message
        if (message.value.trim() === "") {
            errorMessage += "Le champ Message est requis.\n";
            isValid = false;
        }

        // Affichage des erreurs si nécessaires
        if (!isValid) {
            alert(errorMessage);
            event.preventDefault(); // Empêche l'envoi du formulaire si erreur
        }
    });
});
