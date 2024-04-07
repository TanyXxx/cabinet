fetch('https://soltanhamadouche.alwaysdata.net/client/HTML/fragments/modalAjouterConsultation.html')
    .then(response => response.text())
    .then(html => {
        document.getElementById('modalAjouterConsultation').innerHTML = html;
    });