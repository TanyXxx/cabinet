fetch('../HTML/fragments/footer.html') // Assurez-vous que le chemin est correct
            .then(response => response.text())
            .then(html => {
                document.getElementById('footerContainer').innerHTML = html;
            });