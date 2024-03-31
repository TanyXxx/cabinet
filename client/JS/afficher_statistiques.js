console.log("afficher_statistiques.js chargé.");

document.addEventListener('DOMContentLoaded', function() {
    fetchStatsMedecins();
    fetchStatsUsagers();
});

function fetchStatsMedecins() {
    fetch('https://soltanhamadouche.alwaysdata.net/stats/medecins', {
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('jwt')
        }
    })
        .then(response => response.json())
        .then(data => displayStatsMedecins(data.data))
        .catch(error => console.error('Erreur lors de la récupération des statistiques des médecins:', error));
}

function fetchStatsUsagers() {
    fetch('https://soltanhamadouche.alwaysdata.net/stats/usagers', {
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('jwt')
        }
    })
        .then(response => response.json())
        .then(data => displayStatsUsagers(data.data))
        .catch(error => console.error('Erreur lors de la récupération des statistiques des usagers:', error));
}

function displayStatsMedecins(stats) {
    // Créez un titre pour le tableau
    let title = document.createElement('h2');
    title.textContent = 'Durée totale des consultations par médecin';
    // Créez un tableau HTML pour afficher les statistiques
    let table = document.createElement('table');
    table.id = 'medecinsStatsTable';
    table.className = 'table table-striped';

    // Ajoutez les en-têtes de tableau
    let thead = document.createElement('thead');
    let headerRow = document.createElement('tr');
    ['Médecin', 'Total Heures'].forEach(text => {
        let th = document.createElement('th');
        th.textContent = text;
        headerRow.appendChild(th);
    });
    thead.appendChild(headerRow);
    table.appendChild(thead);

    // Ajoutez les lignes de tableau pour chaque statistique
    let tbody = document.createElement('tbody');
    stats.forEach(stat => {
        let row = document.createElement('tr');
        [stat.Medecin, stat.TotalHeures].forEach(text => {
            let td = document.createElement('td');
            td.textContent = text;
            row.appendChild(td);
        });
        tbody.appendChild(row);
    });
    table.appendChild(tbody);

   // Ajoutez le titre et le tableau au conteneur principal
   let container = document.getElementById('statistiquesUsagersContainer');
   // Ajoutez un saut de ligne si le conteneur n'est pas vide
   if (container.innerHTML !== '') {
       container.appendChild(document.createElement('br'));
   }
   container.appendChild(title);
   container.appendChild(table);
}

function displayStatsUsagers(stats) {
    // Créez un titre pour le tableau
    let title = document.createElement('h2');
    title.textContent = 'Répartition des usagers selon leur sexe et leur âge';
    // Créez un tableau HTML pour afficher les statistiques
    let table = document.createElement('table');
    table.id = 'usagersStatsTable';
    table.className = 'table table-striped';

    // Ajoutez les en-têtes de tableau
    let thead = document.createElement('thead');
    let headerRow = document.createElement('tr');
    ['Tranche d\'âge', 'Hommes', 'Femmes'].forEach(text => {
        let th = document.createElement('th');
        th.textContent = text;
        headerRow.appendChild(th);
    });
    thead.appendChild(headerRow);
    table.appendChild(thead);

    // Ajoutez les lignes de tableau pour chaque statistique
    let tbody = document.createElement('tbody');
    stats.forEach(stat => {
        let row = document.createElement('tr');
        [stat.Tranche, stat.Hommes, stat.Femmes].forEach(text => {
            let td = document.createElement('td');
            td.textContent = text;
            row.appendChild(td);
        });
        tbody.appendChild(row);
    });
    table.appendChild(tbody);

    // Ajoutez le titre et le tableau au conteneur principal
    let container = document.getElementById('statistiquesMedecinsContainer');
    // Ajoutez un saut de ligne si le conteneur n'est pas vide
    if (container.innerHTML !== '') {
        container.appendChild(document.createElement('br'));
    }
    container.appendChild(title);
    container.appendChild(table);
}