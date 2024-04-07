fetch('https://soltanhamadouche.alwaysdata.net/client/HTML/fragments/modalModifUsager.html')
    .then(response => response.text())
    .then(html => {
        document.getElementById('modalModifUsager').innerHTML = html;
        if (window.attachEventToEditForm) {
            window.attachEventToEditForm();
        } else {
            console.error("La fonction attachEventToEditForm n'est pas définie.");
        }
    });
