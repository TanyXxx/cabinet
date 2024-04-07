// accessadmin.js
if (!localStorage.getItem('jwt')) {
    window.location.href = 'login.html';
} else {
    var token = localStorage.getItem('jwt');
    var decodedToken = jwt_decode(token);

    if (decodedToken.role !== 'ADMIN') {
        localStorage.setItem('error', 'Accès non autorisé avec ce compte.');
        window.location.href = '../../client/HTML/accueil.html';
    }
}