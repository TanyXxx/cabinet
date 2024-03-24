fetch('../HTML/fragments/menu.html') // Assurez-vous que le chemin est correct
            .then(response => response.text())
            .then(html => {
                document.getElementById('menuContainer').innerHTML = html;
            });