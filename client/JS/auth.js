// auth.js
if (!localStorage.getItem('jwt')) {
    window.location.href = 'https://soltanhamadouche.alwaysdata.net/login'; // Redirige vers la page de connexion si aucun JWT n'est trouvé
}

function logout() {
    localStorage.removeItem('jwt');
    window.location.href = 'https://soltanhamadouche.alwaysdata.net/login'; // Redirige vers la page de connexion après déconnexion
}
