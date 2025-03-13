// Fenêtre modale

  // Ouvrir la modale
  function openModal() {
    document.getElementById("filterModal").style.display = "block";
}

// Fermer la modale
function closeModal() {
    document.getElementById("filterModal").style.display = "none";
}

// Fermer la modale en cliquant en dehors de son contenu
window.onclick = function(event) {
    let modal = document.getElementById("filterModal");
    if (event.target === modal) {
        closeModal();
    }
};