document.addEventListener("DOMContentLoaded", function() {
    let errorBox = document.querySelector(".error-box");
    let errorBtn = document.querySelector(".error-btn");

    if (errorBox && errorBtn) {
        // Ajout d'un effet au survol
        errorBox.addEventListener("mouseover", function() {
            errorBox.style.backgroundColor = "#f5f5f5";
        });

        errorBox.addEventListener("mouseout", function() {
            errorBox.style.backgroundColor = "#ffecec";
        });

        // Ajout d'un délai avant la redirection
        errorBtn.addEventListener("click", function(event) {
            event.preventDefault(); 
            errorBtn.textContent = "Redirection...";
            setTimeout(() => {
                window.location.href = errorBtn.href;
            }, 1000);
        });
    }
});
