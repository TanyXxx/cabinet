fetch('../HTML/fragments/modalModifMedecin.html')
    .then(response => response.text())
    .then(html => {
        document.getElementById('modalModifMedecin').innerHTML = html;
        if (window.attachEventListeners) {
            window.attachEventListeners();
        } else {
            console.error("La fonction attachEventListeners n'est pas définie.");
        }
    });
