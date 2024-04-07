fetch('https://soltanhamadouche.alwaysdata.net/client/HTML/fragments/modalAjouterUsager.html')
    .then(response => response.text())
    .then(html => {
        document.getElementById('modalAjouterUsager').innerHTML = html;
    });
