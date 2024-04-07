console.log("afficher_consultations.js chargé.");

document.addEventListener('DOMContentLoaded', function () {
    if (!localStorage.getItem('jwt')) {
        window.location.href = 'https://soltanhamadouche.alwaysdata.net/login';
    }
    fetchConsultations();
    var addButton = document.querySelector('.btn-ajouter-consultation');
    if (addButton) {
        addButton.addEventListener('click', openAddModal);
    }
    fetchUsagersForDropdown('addUsager');
    fetchUsagersForDropdown('editConsultationUsager');
    fetchMedecinsForDropdown('addMedecin');
    fetchMedecinsForDropdown('editConsultationMedecin');
});

function fetchConsultations() {
    fetch('https://soltanhamadouche.alwaysdata.net/app/consultations', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('jwt')
        }
    })
        .then(response => response.json())
        .then(displayConsultations)
        .catch(error => console.error('Erreur:', error));
}

function displayConsultations(response) {
    if (response.status_code === 200) {
        const consultations = response.data;
        const container = document.getElementById('consultationsContainer');

        if (container && Array.isArray(consultations)) {
            const table = document.createElement('table');
            table.className = 'consultations-table';

            const header = table.createTHead();
            const headerRow = header.insertRow();
            const headers = ['Date', 'Heure', 'Durée (en minutes)','Usager', 'Médecin','Actions'];
            headers.forEach(text => {
                const cell = document.createElement('th');
                cell.textContent = text;
                headerRow.appendChild(cell);
            });

            const tbody = document.createElement('tbody');
            table.appendChild(tbody);

            consultations.forEach(consultation => {
                const row = tbody.insertRow();

                Object.entries(consultation).forEach(([key, value]) => {
                    if (!key.startsWith('ID_')) { // Exclure les champs qui commencent par 'ID_'
                        const cell = row.insertCell();
                        cell.textContent = value;
                    }
                });

                const actionCell = row.insertCell();
                const editButton = document.createElement('a');
                editButton.href = '#';
                editButton.textContent = 'Modifier';
                editButton.onclick = function () {
                    prepareAndShowEditModal(consultation.ID_Consultation);
                    return false;
                };

                const deleteButton = document.createElement('a');
                deleteButton.href = '#';
                deleteButton.textContent = 'Supprimer';
                deleteButton.onclick = function () {
                    if (confirm('Êtes-vous sûr de vouloir supprimer cette consultation?')) {
                        fetch(`https://soltanhamadouche.alwaysdata.net/app/consultations/${consultation.ID_Consultation}`, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'Authorization': 'Bearer ' + localStorage.getItem('jwt')
                            }
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status_code === 200) {
                                    console.log('Suppression réussie:', data);
                                    fetchConsultations();
                                } else {
                                    console.error('Erreur lors de la suppression:', data.status_message);
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

            container.innerHTML = '';
            container.appendChild(table);
        } else {
            console.error('Impossible d\'afficher les consultations: Conteneur manquant ou donnée invalide');
        }
    } else {
        console.error('Erreur lors de la récupération des consultations:', response.status_message);
    }
}

// Rendre la fonction globale
window.attachEventToEditForm = attachEventToEditForm;



function fetchUsagersForDropdown(dropdownId) {
    fetch('https://soltanhamadouche.alwaysdata.net/app/usagers', {
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
            select.innerHTML = '<option value="">Sélectionnez un usager</option>'; // Clear existing options and add a placeholder
            data.data.forEach(usager => {
                const option = document.createElement('option');
                option.value = usager.ID_USAGER;
                option.textContent = `${usager.Civilite} ${usager.Nom} ${usager.Prenom}`;
                select.appendChild(option);
            });
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
    });
}

function fillEditModal(consultation) {
    if (typeof consultation === 'object' && consultation !== null) {
        document.getElementById('editConsultationId').value = consultation.ID_Consultation || '';
        document.getElementById('editConsultationUsager').value = consultation.ID_USAGER || '';
        document.getElementById('editConsultationMedecin').value = consultation.ID_Medecin || '';
        document.getElementById('editConsultationDate').value = consultation.Date_Consultation ? consultation.Date_Consultation.substring(0, 10) : '';
        document.getElementById('editConsultationHeure').value = consultation.Heure || '';
        document.getElementById('editConsultationDuree').value = consultation.Duree || '';

        var editModal = new bootstrap.Modal(document.getElementById('editConsultationModal'), {
            keyboard: false
        });
        editModal.show();
    } else {
        console.error('Les données de la consultation sont invalides.');
    }
}

function attachEventToEditForm() {
    var form = document.getElementById('editConsultationForm');
    if (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            submitEditModalForm();
        });
    } else {
        console.error("Le formulaire 'editConsultationForm' n'a pas été trouvé dans le DOM.");
    }
}

// Rendre la fonction globale
window.attachEventToEditForm = attachEventToEditForm;

function prepareAndShowEditModal(consultationID) {
    fetch(`https://soltanhamadouche.alwaysdata.net/app/consultations/${consultationID}`, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('jwt')
        }
    })
        .then(response => response.json())
        .then(consultation => {
            fillEditModal(consultation.data);
        })
        .catch(error => console.error('Erreur:', error));
}

async function submitEditModalForm() {
    var consultationID = document.getElementById('editConsultationId').value;
    var data = {
        id_usager: document.getElementById('editConsultationUsager').value,
        id_medecin: document.getElementById('editConsultationMedecin').value,
        date_consult: document.getElementById('editConsultationDate').value,
        heure_consult: document.getElementById('editConsultationHeure').value,
        duree_consult: document.getElementById('editConsultationDuree').value,
    };

    fetch(`https://soltanhamadouche.alwaysdata.net/app/consultations/${consultationID}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('jwt')
        },
        body: JSON.stringify(data)
    })
        .then(response => {
            if (!response.ok) {
                return response.json().then(json => Promise.reject(json));
            }
            return response.json();
        })
        .then(responseData => {
            displayApiEditResponseMessage(responseData.status_message, 'success');
            setTimeout(function() {
                $('#editConsultationModal').modal('hide');
            }, 4000);
            fetchConsultations();
        })
        .catch(responseData => {
            displayApiEditResponseMessage(responseData.status_message, 'error');
        });
}

function displayApiEditResponseMessage(message, type) {
    const messageDiv = document.getElementById('EditResponseMessage');
    messageDiv.textContent = message;
    messageDiv.className = 'alert ' + (type === 'error' ? 'alert-danger' : 'alert-success');
    messageDiv.style.display = 'block';
    setTimeout(() => { messageDiv.style.display = 'none'; }, 4000);
}



function openAddModal() {
    var modal = document.getElementById('addConsultationModal');
    modal.querySelector('form').reset();
    var addModal = new bootstrap.Modal(modal);
    addModal.show();
}

function submitAddConsultationForm() {
    const form = document.getElementById('addConsultationForm');
    const data = {
        id_usager: form.elements['usager'].value,
        id_medecin: form.elements['medecin'].value,
        date_consult: form.elements['date'].value,
        heure_consult: form.elements['heure'].value,
        duree_consult: form.elements['duree'].value
    };

    fetch('https://soltanhamadouche.alwaysdata.net/app/consultations', {
        method: 'POST',
        headers: {
            'Authorization': 'Bearer ' + localStorage.getItem('jwt'),
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            // If the response is not ok, we want to convert the response to json
            // and return a rejected promise to enter the catch block
            return response.json().then(json => Promise.reject(json));
        }
        return response.json();
    })
    .then(responseData => {
        displayApiAddResponseMessage(responseData.status_message, 'success');
        fetchConsultations();
        setTimeout(() => {
            $('#addConsultationModal').modal('hide'); // Close the modal
        }, 4000);
    })
    .catch(responseData => {
        // Error handling, either coming from the network, or from the not ok server response
        displayApiAddResponseMessage(responseData.status_message, 'error');
    });
}

function displayApiAddResponseMessage(message, type) {
    const messageDiv = document.getElementById('AddResponseMessage');
    messageDiv.textContent = message;
    messageDiv.className = 'alert ' + (type === 'error' ? 'alert-danger' : 'alert-success');
    messageDiv.style.display = 'block';
    setTimeout(() => { messageDiv.style.display = 'none'; }, 4000);
}

function fetchUsagersForDropdown(dropdownId) {
    fetch('https://soltanhamadouche.alwaysdata.net/app/usagers', {
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
            select.innerHTML = '<option value="">Sélectionnez un usager</option>'; // Clear existing options and add a placeholder
            data.data.forEach(usager => {
                const option = document.createElement('option');
                option.value = usager.ID_USAGER;
                option.textContent = `${usager.Nom} ${usager.Prenom}`;
                select.appendChild(option);
            });
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
    });
}

function fetchMedecinsForDropdown(dropdownId) {
    fetch('https://soltanhamadouche.alwaysdata.net/app/medecins', {
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

fetchMedecinsForDropdown('filterByDoctor');

function filterConsultationsByDoctor() {
    var selectedDoctor = document.getElementById('filterByDoctor').value;

    // Si aucun médecin n'est sélectionné, récupérez toutes les consultations
    if (!selectedDoctor) {
        fetchConsultations();
        return;
    }

    // Sinon, récupérez les consultations pour le médecin sélectionné
    fetch(`https://soltanhamadouche.alwaysdata.net/app/consultations/medecin/${selectedDoctor}`, {
        headers: {
            'Content-Type': 'application/json',
            'Authorization': 'Bearer ' + localStorage.getItem('jwt')
        }
    })
        .then(response => response.json())
        .then(data => {
            displayConsultations(data);
        })
        .catch(error => console.error('Erreur:', error));
}