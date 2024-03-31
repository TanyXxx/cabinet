console.log("afficher_usagers.js chargé.");

document.addEventListener('DOMContentLoaded', function () {
    if (!localStorage.getItem('jwt')) {
        window.location.href = 'login.html';
    }
    fetchUsagers();
    var addButton = document.querySelector('.btn-ajouter-usager');
    if (addButton) {
        addButton.addEventListener('click', openAddModal);
    }
    fetchMedecinsForDropdown('addMedecinRef');
    fetchMedecinsForDropdown('editMedecinRef');
});

function fetchUsagers() {
    fetch('https://soltanhamadouche.alwaysdata.net/usagers', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('jwt')
        }
    })
        .then(response => response.json())
        .then(displayUsagers)
        .catch(error => console.error('Erreur:', error));
}

function displayUsagers(response) {
    if (response.status_code === 200) {
        const usagers = response.data; // On extrait le tableau des usagers de la réponse de l'API
        const container = document.getElementById('usagersContainer');

        if (container && Array.isArray(usagers)) {
            const table = document.createElement('table');
            table.className = 'usagers-table'; // Ajoutez des classes CSS si nécessaire

            // Création de l'en-tête du tableau
            const header = table.createTHead();
            const headerRow = header.insertRow();
            const headers = ['Civilité', 'Nom', 'Prénom', 'Adresse', 'Date de Naissance', 'Numéro de Sécurité Sociale', 'Lieu de Naissance', 'Sexe', 'Code Postal', 'Ville', 'Médecin Référent', 'Actions'];
            headers.forEach(text => {
                const cell = document.createElement('th');
                cell.textContent = text;
                headerRow.appendChild(cell);
            });

            // Création du corps du tableau
            const tbody = document.createElement('tbody');
            table.appendChild(tbody); // Ajout tbody au tableau

            usagers.forEach(usager => {
                const row = tbody.insertRow();

                Object.entries(usager).forEach(([key, value]) => {
                    if (key !== 'ID_USAGER' && key !== 'ID_Medecin_Ref') { // Exclure ID_USAGER et ID_Medecin_Ref
                        const cell = row.insertCell();
                        cell.textContent = value;
                    }
                });

                // Ajout de la colonne d'actions
                const actionCell = row.insertCell();
                const editButton = document.createElement('a');
                editButton.href =
                    editButton.textContent = 'Modifier';
                editButton.onclick = function () {
                    prepareAndShowEditModal(usager.ID_USAGER);
                    return false; // Pour empêcher toute navigation par défaut
                };

                const deleteButton = document.createElement('a');
                deleteButton.href = deleteButton.textContent = 'Supprimer';
                deleteButton.onclick = function () {
                    if (confirm('Êtes-vous sûr de vouloir supprimer cet usager?')) {
                        fetch(`https://soltanhamadouche.alwaysdata.net/usagers/${usager.ID_USAGER}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'Authorization': 'Bearer ' + localStorage.getItem('jwt')
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status_code === 200) {
                                    fetchUsagers(); // Actualiser la liste des usagers
                                    displayApiDeleteResponseMessage(data.status_message, 'success');
                                } else {
                                    console.error('Erreur lors de la suppression:', data.status_message);
                                    displayApiDeleteResponseMessage(data.status_message, 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Erreur:', error);
                            });
                    }
                    return false;
                };

                actionCell.appendChild(editButton);
                actionCell.appendChild(document.createTextNode(' | '));
                actionCell.appendChild(deleteButton);
            });

            // Ajout du tableau au conteneur
            container.innerHTML = ''; // Efface le contenu actuel
            container.appendChild(table);
        } else {
            console.error('Impossible d\'afficher les usagers: Conteneur manquant ou donnée invalide');
        }
    } else {
        console.error('Erreur lors de la récupération des usagers:', response.status_message);
    }
}

function fillEditModal(usager) {
    // Assurez-vous que l'usager est un objet contenant les bonnes propriétés.
    if (typeof usager === 'object' && usager !== null) {
        document.getElementById('editId').value = usager.ID_USAGER || '';
        document.getElementById('editCivilite').value = usager.Civilite || '';
        document.getElementById('editSexe').value = usager.Sexe || '';
        document.getElementById('editNom').value = usager.Nom || '';
        document.getElementById('editPrenom').value = usager.Prenom || '';
        document.getElementById('editAdresse').value = usager.Adresse || '';
        document.getElementById('editCodePostal').value = usager.Code_Postal || '';
        document.getElementById('editVille').value = usager.Ville || '';
        document.getElementById('editLieuNaissance').value = usager.Lieu_Naissance || '';
        document.getElementById('editDateNaissance').value = usager.Date_Naissance ? usager.Date_Naissance.substring(0, 10) : ''; // Assurez-vous de formater la date correctement.
        document.getElementById('editNumeroSecu').value = usager.Numero_Secu || '';
        document.getElementById('editMedecinRef').value = usager.ID_Medecin_Ref || '';

        // Affichez le modal après avoir rempli les champs.
        var editModal = new bootstrap.Modal(document.getElementById('editUsagerModal'), {
            keyboard: false
        });
        editModal.show();
    } else {
        console.error('Les données de l\'usager sont invalides.');
    }
}

function prepareAndShowEditModal(usagerID) {
    fetch(`https://soltanhamadouche.alwaysdata.net/usagers/${usagerID}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('jwt')
        }
    })
        .then(response => response.json())
        .then(usager => {
            fillEditModal(usager.data); // Supposons que `usager.data` contient les infos de l'usager
        })
        .catch(error => console.error('Erreur:', error));
}

function attachEventToEditForm() {
    var form = document.getElementById('editUsagerForm');
    if (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            submitEditModalForm();
        });
    } else {
        console.error("Le formulaire 'editUsagerForm' n'a pas été trouvé dans le DOM.");
    }
}

// Rendre la fonction globale
window.attachEventToEditForm = attachEventToEditForm;

function submitEditModalForm() {
    var usagerID = document.getElementById('editId').value;
    var data = {
        Civilite: document.getElementById('editCivilite').value,
        Nom: document.getElementById('editNom').value,
        Prenom: document.getElementById('editPrenom').value,
        Sexe: document.getElementById('editSexe').value,
        Adresse: document.getElementById('editAdresse').value,
        Code_Postal: document.getElementById('editCodePostal').value,
        Ville: document.getElementById('editVille').value,
        Date_Naissance: document.getElementById('editDateNaissance').value,
        Lieu_Naissance: document.getElementById('editLieuNaissance').value,
        Numero_Secu: document.getElementById('editNumeroSecu').value,
        ID_Medecin_Ref: document.getElementById('editMedecinRef').value,
    };

    fetch(`https://soltanhamadouche.alwaysdata.net/usagers/${usagerID}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('jwt')
        },
        body: JSON.stringify(data)
    })
        .then(response => {
            // Vérifiez toujours le statut de la réponse avant de traiter la réponse
            if (!response.ok) {
                // Si la réponse n'est pas OK, transformez le corps de réponse en JSON
                // cela va aller dans le catch si le réseau a échoué ou si la réponse ne peut pas être parsée
                return response.json().then(json => Promise.reject(json));
            }
            return response.json();
        })
        .then(responseData => {
            displayApiResponseMessage(responseData.status_message, 'success');
            fetchUsagers();
            setTimeout(() => {
            $('#editUsagerModal').modal('hide'); // Fermez le modal
            }, 2000);
        })
        .catch(responseData => {
            // Ici responseData est l'objet JSON transformé dans le premier then en cas de réponse non OK
            displayApiResponseMessage(responseData.status_message, 'error');
        });
}

function displayApiResponseMessage(message, type) {
    const messageDiv = document.getElementById('responseMessage');
    messageDiv.textContent = message; // Message provenant de l'API
    messageDiv.className = 'alert ' + (type === 'error' ? 'alert-danger' : 'alert-success');
    messageDiv.style.display = 'block'; // Assurez-vous que votre élément est visible
    setTimeout(() => { messageDiv.style.display = 'none'; }, 4000); // Cache le message après 4 secondes
}


function openAddModal() {
    var modal = document.getElementById('addUsagerModal');
    modal.querySelector('form').reset();
    var addModal = new bootstrap.Modal(modal);
    addModal.show();
}

function formatDateForServer(dateString) {
    var parts = dateString.split('-');
    return parts[2] + '/' + parts[1] + '/' + parts[0]; // Convertit yyyy-mm-dd en dd/mm/yyyy
}

// La fonction pour traiter la soumission du formulaire d'ajout
function submitAddUsagerForm() {
    var formattedDate = formatDateForServer(document.getElementById('addDateNaissance').value);
    var data = {
        civilite: document.getElementById('addCivilite').value,
        nom: document.getElementById('addNom').value,
        prenom: document.getElementById('addPrenom').value,
        sexe: document.getElementById('addSexe').value,
        adresse: document.getElementById('addAdresse').value,
        code_postal: document.getElementById('addCodePostal').value,
        ville: document.getElementById('addVille').value,
        date_nais: formattedDate,
        lieu_nais: document.getElementById('addLieuNaissance').value,
        num_secu: document.getElementById('addNumeroSecu').value,
        ID_Medecin_Ref: document.getElementById('addMedecinRef').value,
    };

    fetch('https://soltanhamadouche.alwaysdata.net/usagers', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('jwt')
        },
        body: JSON.stringify(data)
    })
        .then(response => {
            if (!response.ok) {
                // Si la réponse n'est pas ok, nous voulons convertir la réponse en json
                // et renvoyer une promesse rejetée pour entrer dans le bloc catch
                return response.json().then(json => Promise.reject(json));
            }
            return response.json();
        })
        .then(responseData => {
            displayApiAddResponseMessage(responseData.status_message, 'success');
            fetchUsagers();
            setTimeout(() => {
                $('#addUsagerModal').modal('hide'); // Fermez le modal
            }, 2000);
        })
        .catch(responseData => {
            // Gestion des erreurs, soit venant du réseau, soit de la réponse non ok du serveur
            displayApiAddResponseMessage(responseData.status_message, 'error');
        });
}

function displayApiAddResponseMessage(message, type) {
    const messageDiv = document.getElementById('addResponseMessage');
    messageDiv.textContent = message; // Message provenant de l'API
    messageDiv.className = 'alert ' + (type === 'error' ? 'alert-danger' : 'alert-success');
    messageDiv.style.display = 'block'; // Assurez-vous que votre élément est visible
    setTimeout(() => { messageDiv.style.display = 'none'; }, 4000); // Cache le message après 4 secondes
}

function displayApiDeleteResponseMessage(message, type) {
    const messageDiv = document.getElementById('deleteResponseMessage');
    messageDiv.textContent = message; // Message provenant de l'API
    messageDiv.className = 'alert ' + (type === 'error' ? 'alert-danger' : 'alert-success');
    messageDiv.style.display = 'block'; // Assurez-vous que votre élément est visible
    setTimeout(() => { messageDiv.style.display = 'none'; }, 4000); // Cache le message après 4 secondes
}


function fetchMedecinsForDropdown(dropdownId) {
    fetch('https://soltanhamadouche.alwaysdata.net/medecins', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('jwt')
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.status_code === 200 && Array.isArray(data.data)) {
                const select = document.getElementById(dropdownId);
                select.innerHTML = '<option value="">Sélectionnez un médecin référent</option>'; // Clear existing options and add a placeholder
                data.data.forEach(medecin => {
                    const option = document.createElement('option');
                    option.value = medecin.ID_Medecin;
                    option.textContent = `${medecin.Civilite} ${medecin.Nom} ${medecin.Prenom}`;
                    select.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
}
