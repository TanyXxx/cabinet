-- Création et insertion pour la table medecin
CREATE TABLE medecin (
    ID_Medecin int(11) NOT NULL AUTO_INCREMENT,
    Civilite varchar(10) DEFAULT NULL,
    Nom varchar(50) DEFAULT NULL,
    Prenom varchar(50) DEFAULT NULL,
    PRIMARY KEY (ID_Medecin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO medecin (Civilite, Nom, Prenom) VALUES
('Monsieur', 'Dupont', 'Jean'),
('Madame', 'Martin', 'Anne'),
('Monsieur', 'Durand', 'Pierre'),
('Madame', 'Lefebvre', 'Sophie');

-- Création et insertion pour la table usager
CREATE TABLE usager (
    ID_USAGER int(11) NOT NULL AUTO_INCREMENT,
    Civilite varchar(10) DEFAULT NULL,
    Nom varchar(50) DEFAULT NULL,
    Prenom varchar(50) DEFAULT NULL,
    Adresse varchar(100) DEFAULT NULL,
    Date_Naissance date DEFAULT NULL,
    Numero_Secu varchar(13) DEFAULT NULL,
    Lieu_Naissance varchar(50) DEFAULT NULL,
    ID_Medecin_Ref int(11) DEFAULT NULL,
    PRIMARY KEY (ID_USAGER),
    FOREIGN KEY (ID_Medecin_Ref) REFERENCES medecin (ID_Medecin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO usager (Civilite, Nom, Prenom, Adresse, Date_Naissance, Numero_Secu, Lieu_Naissance, ID_Medecin_Ref) VALUES
('Monsieur', 'Bernard', 'Luc', '123 rue des Fleurs, Paris', '1990-05-12', '1234567890123', 'Paris', 1),
('Madame', 'Petit', 'Marie', '456 avenue des Lilas, Lyon', '1985-02-20', '2345678901234', 'Lyon', 2),
('Monsieur', 'Roux', 'François', '789 boulevard des Roses, Marseille', '1975-11-30', '3456789012345', 'Marseille', 3),
('Madame', 'Leroy', 'Julie', '111 allée des Orchidées, Toulouse', '2000-08-15', '4567890123456', 'Toulouse', 4);

-- Création de la table consultation
CREATE TABLE consultation (
    ID_Consultation int(11) NOT NULL AUTO_INCREMENT,
    ID_USAGER int(11) DEFAULT NULL,
    ID_Medecin int(11) DEFAULT NULL,
    Date_Consultation date DEFAULT NULL,
    Heure time DEFAULT NULL,
    Duree int(11) DEFAULT NULL,
    PRIMARY KEY (ID_Consultation),
    FOREIGN KEY (ID_USAGER) REFERENCES usager (ID_USAGER),
    FOREIGN KEY (ID_Medecin) REFERENCES medecin (ID_Medecin)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insertion des consultations
INSERT INTO consultation (ID_USAGER, ID_Medecin, Date_Consultation, Heure, Duree) VALUES
(1, 1, '2023-12-01', '08:00', 30),
(2, 2, '2023-12-01', '09:00', 30),
(3, 3, '2023-12-01', '10:00', 30),
(4, 4, '2023-12-01', '11:00', 30);

-- Création de la table utilisateurs
CREATE TABLE utilisateurs (
    ID_Utilisateur int(11) NOT NULL AUTO_INCREMENT,
    NomUtilisateur varchar(50) NOT NULL,
    MotDePasse varchar(255) NOT NULL,
    Role varchar(50) DEFAULT NULL,
    PRIMARY KEY (ID_Utilisateur),
    UNIQUE KEY NomUtilisateur (NomUtilisateur)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insertion des utilisateurs
INSERT INTO utilisateurs (NomUtilisateur, MotDePasse, Role) VALUES
('admin', 'admin', 'ADMIN'),
('secretaire', 'password123', 'SECRETAIRE');