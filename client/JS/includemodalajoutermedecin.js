fetch('../HTML/fragments/modalAjouterMedecin.html')
    .then(response => response.text())
    .then(html => {
        document.getElementById('modalAjouterMedecin').innerHTML = html;
    });
