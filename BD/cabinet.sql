-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : ven. 19 jan. 2024 à 21:33
-- Version du serveur : 10.4.28-MariaDB
-- Version de PHP : 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `cabinet`
--

-- --------------------------------------------------------

--
-- Structure de la table `consultation`
--

CREATE TABLE `consultation` (
  `ID_Consultation` int(11) NOT NULL,
  `ID_USAGER` int(11) DEFAULT NULL,
  `ID_Medecin` int(11) DEFAULT NULL,
  `Date_Consultation` date DEFAULT NULL,
  `Heure` time DEFAULT NULL,
  `Duree` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `consultation`
--

INSERT INTO `consultation` (`ID_Consultation`, `ID_USAGER`, `ID_Medecin`, `Date_Consultation`, `Heure`, `Duree`) VALUES
(12, 1, 1, '2023-12-01', '08:00:00', 30),
(13, 2, 2, '2023-12-01', '09:00:00', 30),
(14, 3, 3, '2023-12-01', '10:00:00', 30),
(15, 4, 4, '2023-12-01', '11:00:00', 30),
(16, 5, 1, '2023-12-02', '14:30:00', 45),
(17, 6, 2, '2023-12-02', '15:30:00', 45),
(18, 7, 3, '2023-12-03', '10:30:00', 30),
(19, 8, 4, '2023-12-03', '11:30:00', 30);

-- --------------------------------------------------------

--
-- Structure de la table `medecin`
--

CREATE TABLE `medecin` (
  `ID_Medecin` int(11) NOT NULL,
  `Civilite` varchar(10) DEFAULT NULL,
  `Nom` varchar(50) DEFAULT NULL,
  `Prenom` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `medecin`
--

INSERT INTO `medecin` (`ID_Medecin`, `Civilite`, `Nom`, `Prenom`) VALUES
(1, 'Monsieur', 'Dubois', 'Philippe'),
(2, 'Madame', 'Girard', 'Marie'),
(3, 'Monsieur', 'Lefort', 'Thomas'),
(4, 'Madame', 'Bertrand', 'Sophie'),
(5, 'Madame', 'Dufour', 'Isabelle'),
(6, 'Monsieur', 'Leroux', 'Jean-Pierre'),
(7, 'Madame', 'Moreau', 'Catherine');

-- --------------------------------------------------------

--
-- Structure de la table `usager`
--

CREATE TABLE `usager` (
  `ID_USAGER` int(11) NOT NULL,
  `Civilite` varchar(10) DEFAULT NULL,
  `Nom` varchar(50) DEFAULT NULL,
  `Prenom` varchar(50) DEFAULT NULL,
  `Adresse` varchar(100) DEFAULT NULL,
  `Date_Naissance` date DEFAULT NULL,
  `Numero_Secu` varchar(13) DEFAULT NULL,
  `Lieu_Naissance` varchar(50) DEFAULT NULL,
  `ID_Medecin_Ref` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `usager`
--

INSERT INTO `usager` (`ID_USAGER`, `Civilite`, `Nom`, `Prenom`, `Adresse`, `Date_Naissance`, `Numero_Secu`, `Lieu_Naissance`, `ID_Medecin_Ref`) VALUES
(1, 'Monsieur', 'Martin', 'Luc', '123 rue de la République, Paris', '1988-04-25', '1234567890123', 'Paris', 1),
(2, 'Madame', 'Dubois', 'Marie', '456 avenue des Lilas, Lyon', '1979-09-15', '2345678901234', 'Lyon', 2),
(3, 'Monsieur', 'Roux', 'François', '789 boulevard des Roses, Marseille', '1985-02-20', '3456789012345', 'Marseille', 3),
(4, 'Madame', 'Leroy', 'Julie', '111 allée des Orchidées, Toulouse', '1992-07-10', '4567890123456', 'Toulouse', 4),
(5, 'Madame', 'Dupuis', 'Caroline', '567 avenue des Champs, Bordeaux', '1995-03-18', '5678901234567', 'Bordeaux', 1),
(6, 'Monsieur', 'Lefebvre', 'Antoine', '234 rue de la Liberté, Lille', '1983-11-05', '6789012345678', 'Lille', 2),
(7, 'Madame', 'Garcia', 'Sophia', '987 boulevard des Roses, Nice', '2001-06-22', '7890123456789', 'Nice', 3),
(8, 'Monsieur', 'Martin', 'Thierry', '456 chemin des Étoiles, Lyon', '1976-09-30', '8901234567890', 'Lyon', 4);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `ID_Utilisateur` int(11) NOT NULL,
  `NomUtilisateur` varchar(50) NOT NULL,
  `MotDePasse` varchar(255) NOT NULL,
  `Role` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`ID_Utilisateur`, `NomUtilisateur`, `MotDePasse`, `Role`) VALUES
(2, 'admin', 'admin', 'ADMIN'),
(3, 'secretaire', 'password123', 'SECRETAIRE');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `consultation`
--
ALTER TABLE `consultation`
  ADD PRIMARY KEY (`ID_Consultation`),
  ADD KEY `ID_USAGER` (`ID_USAGER`),
  ADD KEY `ID_Medecin` (`ID_Medecin`);

--
-- Index pour la table `medecin`
--
ALTER TABLE `medecin`
  ADD PRIMARY KEY (`ID_Medecin`);

--
-- Index pour la table `usager`
--
ALTER TABLE `usager`
  ADD PRIMARY KEY (`ID_USAGER`),
  ADD KEY `ID_Medecin_Ref` (`ID_Medecin_Ref`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`ID_Utilisateur`),
  ADD UNIQUE KEY `NomUtilisateur` (`NomUtilisateur`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `consultation`
--
ALTER TABLE `consultation`
  MODIFY `ID_Consultation` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `medecin`
--
ALTER TABLE `medecin`
  MODIFY `ID_Medecin` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT pour la table `usager`
--
ALTER TABLE `usager`
  MODIFY `ID_USAGER` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `ID_Utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `consultation`
--
ALTER TABLE `consultation`
  ADD CONSTRAINT `consultation_ibfk_1` FOREIGN KEY (`ID_USAGER`) REFERENCES `usager` (`ID_USAGER`),
  ADD CONSTRAINT `consultation_ibfk_2` FOREIGN KEY (`ID_Medecin`) REFERENCES `medecin` (`ID_Medecin`);

--
-- Contraintes pour la table `usager`
--
ALTER TABLE `usager`
  ADD CONSTRAINT `usager_ibfk_1` FOREIGN KEY (`ID_Medecin_Ref`) REFERENCES `medecin` (`ID_Medecin`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
