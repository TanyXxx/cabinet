fetch('https://soltanhamadouche.alwaysdata.net/client/HTML/fragments/modalModifConsultation.html')
    .then(response => response.text())
    .then(html => {
        document.getElementById('modalModifConsultation').innerHTML = html;
        if (window.attachEventToEditForm) {
            window.attachEventToEditForm();
        } else {
            console.error("La fonction attachEventToEditForm n'est pas définie.");
        }
    });