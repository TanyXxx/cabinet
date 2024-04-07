document.getElementById('loginForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const errorMessage = document.getElementById('errorMessage');

    fetch('https://noahbeugnet.alwaysdata.net/auth', { // Remplacez par l'URL correcte de votre AuthAPI
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ login: username, mdp: password })
    })
    .then(response => response.json())
    .then(data => {
        if(data.data.jwt) {
            // Connexion réussie, sauvegarde du JWT et redirection
            console.log("Connexion réussie. JWT:", data.data.jwt);
            localStorage.setItem('jwt', data.data.jwt); // Sauvegarde du JWT dans le stockage local
            window.location.href = 'https://soltanhamadouche.alwaysdata.net/client/HTML/accueil.html'; // Redirection vers la page d'accueil
        } else {
            // Affichage du message d'erreur
            errorMessage.textContent = 'Login ou mot de passe incorrect';
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        errorMessage.textContent = 'Erreur de connexion';
    });
});
