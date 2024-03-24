fetch('../HTML/fragments/modalAjouterUsager.html')
    .then(response => response.text())
    .then(html => {
        document.getElementById('modalAjouterUsager').innerHTML = html;
        if (window.attachEventToEditForm) {
            window.attachEventToEditForm();
        } else {
            console.error("La fonction attachEventToEditForm n'est pas définie.");
        }
    });
