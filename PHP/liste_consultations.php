
<?php
include 'BD.php';

// Récupérer l'ID du médecin sélectionné si présent
$medecinSelectionne = isset($_GET['medecin']) ? $_GET['medecin'] : '';


// Afficher le menu déroulant pour sélectionner un médecin
echo "<form class='form-consultation' action='' method='get'>";
echo "Filtrer par médecin: <select class='select-consultation' name='medecin' onchange='this.form.submit()'>";
echo "<option value=''>Tous les médecins</option>";

// Générer les options pour le menu déroulant
$sqlMedecins = "SELECT ID_Medecin, Nom, Prenom FROM medecin";
foreach ($conn->query($sqlMedecins) as $medecin) {
    $selected = $medecin['ID_Medecin'] == $medecinSelectionne ? 'selected' : '';
    echo "<option value='" . $medecin['ID_Medecin'] . "' $selected>" . $medecin['Nom'] . " " . $medecin['Prenom'] . "</option>";
}
echo "</select>";
echo "</form>";

// Construire la requête SQL de base
$sql = "SELECT consultation.ID_Consultation, usager.Nom as UsagerNom, usager.Prenom as UsagerPrenom, medecin.Nom as MedecinNom, medecin.Prenom as MedecinPrenom, consultation.Date_Consultation, consultation.Heure, consultation.Duree 
        FROM consultation 
        JOIN usager ON consultation.ID_USAGER = usager.ID_USAGER 
        JOIN medecin ON consultation.ID_Medecin = medecin.ID_Medecin";

// Ajouter le filtre si un médecin est sélectionné
if (!empty($medecinSelectionne)) {
    $sql .= " WHERE consultation.ID_Medecin = :medecinSelectionne";
}

$sql .= " ORDER BY consultation.Date_Consultation DESC, consultation.Heure DESC";

// Préparer et exécuter la requête
$stmt = $conn->prepare($sql);
if (!empty($medecinSelectionne)) {
    $stmt->bindParam(':medecinSelectionne', $medecinSelectionne, PDO::PARAM_INT);
}
$stmt->execute();

// Afficher les résultats
if ($stmt->rowCount() > 0) {
    echo "<table>";
    echo "<tr><th>Usager</th><th>Médecin</th><th>Date de Consultation</th><th>Heure</th><th>Durée (minutes)</th><th>Actions</th></tr>";

    while ($row = $stmt->fetch()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['UsagerNom']) . " " . htmlspecialchars($row['UsagerPrenom']) . "</td>";
        echo "<td>" . htmlspecialchars($row['MedecinNom']) . " " . htmlspecialchars($row['MedecinPrenom']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Date_Consultation']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Heure']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Duree']) . "</td>";
        echo "<td><a href='modifier_consultation.php?id=" . $row['ID_Consultation'] . "'>Modifier</a> | <a href='supprimer_consultation.php?id=" . $row['ID_Consultation'] . "'>Supprimer</a></td>";
        echo "</tr>";
    }

    echo "</table>";
    ?>
    <div class="div-btn-liste-consultation">    
        <?php
        echo "<button class='btn-ajouter-consultation'onclick=\"window.location.href='saisir_consultation.php'\">Ajouter Consultation</button>";}
        ?>
</div>
</div>