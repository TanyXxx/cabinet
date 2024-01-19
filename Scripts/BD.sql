-- Création et insertion pour la table medecin
CREATE TABLE medecin (
    ID_Medecin int(11) NOT NULL AUTO_INCREMENT,
    Civilite varchar(10) DEFAULT NULL,
    Nom varchar(50) DEFAULT NULL,
    Prenom varchar(50) DEFAULT NULL,
    PRIMARY KEY (ID_Medecin)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO medecin (Civilite, Nom, Prenom) VALUES
('Monsieur', 'Dubois', 'Philippe'),
('Madame', 'Girard', 'Marie'),
('Monsieur', 'Lefort', 'Thomas'),
('Madame', 'Bertrand', 'Sophie'),
('Madame', 'Dufour', 'Isabelle'),
('Monsieur', 'Leroux', 'Jean-Pierre'),
('Madame', 'Moreau', 'Catherine');

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
('Monsieur', 'Martin', 'Luc', '123 rue de la République, Paris', '1988-04-25', '1234567890123', 'Paris', 1),
('Madame', 'Dubois', 'Marie', '456 avenue des Lilas, Lyon', '1979-09-15', '2345678901234', 'Lyon', 2),
('Monsieur', 'Roux', 'François', '789 boulevard des Roses, Marseille', '1985-02-20', '3456789012345', 'Marseille', 3),
('Madame', 'Leroy', 'Julie', '111 allée des Orchidées, Toulouse', '1992-07-10', '4567890123456', 'Toulouse', 4),
('Madame', 'Dupuis', 'Caroline', '567 avenue des Champs, Bordeaux', '1995-03-18', '5678901234567', 'Bordeaux', 1),
('Monsieur', 'Lefebvre', 'Antoine', '234 rue de la Liberté, Lille', '1983-11-05', '6789012345678', 'Lille', 2),
('Madame', 'Garcia', 'Sophia', '987 boulevard des Roses, Nice', '2001-06-22', '7890123456789', 'Nice', 3),
('Monsieur', 'Martin', 'Thierry', '456 chemin des Étoiles, Lyon', '1976-09-30', '8901234567890', 'Lyon', 4);

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
(4, 4, '2023-12-01', '11:00', 30),
(5, 1, '2023-12-02', '14:30', 45),
(6, 2, '2023-12-02', '15:30', 45),
(7, 3, '2023-12-03', '10:30', 30),
(8, 4, '2023-12-03', '11:30', 30);

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
