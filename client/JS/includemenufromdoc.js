fetch('../../client/HTML/fragments/menufordoc.html') // Assurez-vous que le chemin est correct
            .then(response => response.text())
            .then(html => {
                document.getElementById('menuContainer').innerHTML = html;
            });