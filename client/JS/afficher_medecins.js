console.log("afficher_medecins.js chargé.");

document.addEventListener('DOMContentLoaded', function () {
    if (!localStorage.getItem('jwt')) {
        window.location.href = 'https://soltanhamadouche.alwaysdata.net/login';
    }
    fetchMedecins();
    var addButton = document.querySelector('.btn-ajouter-medecin');
    if (addButton) {
        addButton.addEventListener('click', openAddModal);
    } 
});

function attachEventListeners() {
    var form = document.getElementById('editMedecinForm');
    if (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            submitEditModalForm();
        });
    } else {
        console.error("Le formulaire 'editMedecinForm' n'a pas été trouvé dans le DOM.");
    }
}

window.attachEventListeners = attachEventListeners;

function fetchMedecins() {
    fetch('https://soltanhamadouche.alwaysdata.net/app/medecins', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('jwt')
        }
    })
        .then(response => response.json())
        .then(displayMedecins)
        .catch(error => console.error('Erreur:', error));
}


function displayMedecins(response) {
    if (response.status_code === 200) {
        const medecins = response.data;
        const container = document.getElementById('medecinsContainer');

        if (container && Array.isArray(medecins)) {
            const table = document.createElement('table');
            table.className = 'medecins-table';

            const header = table.createTHead();
            const headerRow = header.insertRow();
            const headers = ['Civilité', 'Nom', 'Prénom', 'Actions'];
            headers.forEach(text => {
                const cell = document.createElement('th');
                cell.textContent = text;
                headerRow.appendChild(cell);
            });

            const tbody = document.createElement('tbody');
            table.appendChild(tbody);

            medecins.forEach(medecin => {
                const row = tbody.insertRow();

                Object.entries(medecin).forEach(([key, value]) => {
                    if (key !== 'ID_Medecin') {
                        const cell = row.insertCell();
                        cell.textContent = value;
                    }
                });

                const actionCell = row.insertCell();
                const editButton = document.createElement('a');
                editButton.href = editButton.textContent = 'Modifier';
                editButton.onclick = function () {
                    prepareAndShowEditModal(medecin.ID_Medecin);
                    return false;
                };

                const deleteButton = document.createElement('a');
                deleteButton.href = deleteButton.textContent = 'Supprimer';
                deleteButton.onclick = function () {
                        deleteMedecin(medecin.ID_Medecin);
                    return false;
                };

                actionCell.appendChild(editButton);
                actionCell.appendChild(document.createTextNode(' | '));
                actionCell.appendChild(deleteButton);
            });

            container.innerHTML = '';
            container.appendChild(table);
        } else {
            console.error('Impossible d\'afficher les médecins: Conteneur manquant ou donnée invalide');
        }
    } else {
        console.error('Erreur lors de la récupération des médecins:', response.status_message);
    }
}

function prepareAndShowEditModal(medecinID) {
    fetch(`https://soltanhamadouche.alwaysdata.net/app/medecins/${medecinID}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('jwt')
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.status_code === 200) {
                fillEditModal(data.data);
                // Assuming your modal is correctly referenced in your HTML
                $('#editMedecinModal').modal('show');
            } else {
                alert("Erreur lors de la récupération des informations du médecin.");
            }
        })
        .catch(error => console.error('Erreur:', error));
}

function fillEditModal(medecin) {
    if (typeof medecin === 'object' && medecin !== null) {
        // Make sure these IDs match the IDs in your modalModifMedecin.html
        document.getElementById('editCiviliteMedecin').value = medecin.Civilite || '';
        document.getElementById('editNomMedecin').value = medecin.Nom || '';
        document.getElementById('editPrenomMedecin').value = medecin.Prenom || '';
        document.getElementById('editIdMedecin').value = medecin.ID_Medecin || '';

        // Show the modal after filling the fields.
        $('#editMedecinModal').modal('show');
    } else {
        console.error('Les données du medecin sont invalides.');
    }
}




function deleteMedecin(medecinID) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce médecin?')) {
        fetch(`https://soltanhamadouche.alwaysdata.net/app/medecins/${medecinID}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('jwt')
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.status_code === 200) {
                    console.log('Médecin supprimé avec succès');
                    fetchMedecins(); // Recharger la liste des médecins
                } else {
                    alert("Erreur lors de la suppression du médecin.");
                }
            })
            .catch(error => console.error('Erreur:', error));
    }
}


function openAddModal() {
    var modal = document.getElementById('addMedecinModal');
    modal.querySelector('form').reset();
    var addModal = new bootstrap.Modal(modal);
    addModal.show();
}


function submitAddMedecinForm() {
    var data = {
        civilite: document.getElementById('addCivilite').value,
        nom: document.getElementById('addNom').value,
        prenom: document.getElementById('addPrenom').value
    };

    fetch('https://soltanhamadouche.alwaysdata.net/app/medecins', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('jwt')
        },
        body: JSON.stringify(data)
    })
        .then(response => {
            if (!response.ok) {
                // Convert the response to json and return a rejected promise to enter the catch block
                return response.json().then(json => Promise.reject(json));
            }
            return response.json();
        })
        .then(responseData => {
            displayApiAddResponseMessage(responseData.status_message, 'success');
            fetchMedecins(); // Refresh the list of medecins
            setTimeout(() => {
                $('#addMedecinModal').modal('hide'); // Fermez le modal
            }, 3000);
        })
        .catch(responseData => {
            // Handle errors, either from network or non-ok server response
            displayApiAddResponseMessage(responseData.status_message, 'error');
        });
}



function displayApiAddResponseMessage(message, type) {
    const messageElement = document.getElementById('addMedecinResponseMessage');
    messageElement.textContent = message;
    messageElement.className = type === 'success' ? 'alert alert-success' : 'alert alert-danger';
    messageElement.style.display = 'block';

    // Optionally, hide the message after a delay
    setTimeout(() => {
        messageElement.style.display = 'none';
    }, 5000);
}

async function submitEditModalForm() {
    var data = {
        civilite: document.getElementById('editCiviliteMedecin').value,
        nom: document.getElementById('editNomMedecin').value,
        prenom: document.getElementById('editPrenomMedecin').value
    };

    try {
        const response = await fetch(`https://soltanhamadouche.alwaysdata.net/app/medecins/${document.getElementById('editIdMedecin').value}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': 'Bearer ' + localStorage.getItem('jwt')
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            // Convert the response to json and throw an error to enter the catch block
            const responseData = await response.json();
            throw new Error(responseData.status_message);
        }

        const responseData = await response.json();
        displayApiEditResponseMessage(responseData.status_message, 'success');
        await fetchMedecins(); // Refresh the list of medecins
        setTimeout(() => {
            $('#editMedecinModal').modal('hide'); // Fermez le modal
        }, 3000);
    } catch (error) {
        // Handle errors, either from network or non-ok server response
        displayApiEditResponseMessage(error.message, 'error');
    }
}

function displayApiEditResponseMessage(message, type) {
    const messageElement = document.getElementById('responseMessageMedecin');
    messageElement.textContent = message;
    messageElement.className = type === 'success' ? 'alert alert-success' : 'alert alert-danger';
    messageElement.style.display = 'block';

    // Optionally, hide the message after a delay
    setTimeout(() => {
        messageElement.style.display = 'none';
    }, 5000);
}