-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : jeu. 23 jan. 2025 à 11:08
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `stage`
--

-- --------------------------------------------------------

--
-- Structure de la table `lien_num_serie`
--

CREATE TABLE `lien_num_serie` (
  `id_lien_num_serie` int(11) NOT NULL,
  `id_num_serie_parent` varchar(150) DEFAULT NULL,
  `id_num_serie_enfant` varchar(150) DEFAULT NULL,
  `type` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `mouvement`
--

CREATE TABLE `mouvement` (
  `id_mouvement` int(11) NOT NULL,
  `id_num_serie` varchar(150) DEFAULT NULL,
  `status` varchar(150) DEFAULT NULL,
  `date_passage` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `num_series`
--

CREATE TABLE `num_series` (
  `id_num_serie` varchar(150) NOT NULL,
  `id_produit` int(11) NOT NULL,
  `id_status_numserie` int(11) NOT NULL,
  `num_serie` varchar(150) NOT NULL,
  `cree_par` varchar(150) NOT NULL,
  `date_creation` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

CREATE TABLE `produits` (
  `id_produit` int(11) NOT NULL,
  `id_status_produit` int(11) NOT NULL,
  `id_modelle_produit` int(11) NOT NULL,
  `indice` int(11) NOT NULL,
  `libelle` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `proprietes_num_serie`
--

CREATE TABLE `proprietes_num_serie` (
  `id_prop` int(11) NOT NULL,
  `id_num_serie` varchar(150) DEFAULT NULL,
  `valeur` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `status_num_serie`
--

CREATE TABLE `status_num_serie` (
  `id_status_num_serie` int(11) NOT NULL,
  `etat_num_serie` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `status_produit`
--

CREATE TABLE `status_produit` (
  `id_status_produit` int(11) NOT NULL,
  `etat_produit` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `identifiant` varchar(50) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('admin','consultant') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `identifiant`, `mot_de_passe`, `role`) VALUES
(1, 'admin', 'admin123', 'admin'),
(2, 'consultant', 'consult123', 'consultant');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `lien_num_serie`
--
ALTER TABLE `lien_num_serie`
  ADD PRIMARY KEY (`id_lien_num_serie`),
  ADD KEY `id_num_serie_parent` (`id_num_serie_parent`),
  ADD KEY `id_num_serie_enfant` (`id_num_serie_enfant`);

--
-- Index pour la table `mouvement`
--
ALTER TABLE `mouvement`
  ADD PRIMARY KEY (`id_mouvement`),
  ADD KEY `id_num_serie` (`id_num_serie`);

--
-- Index pour la table `num_series`
--
ALTER TABLE `num_series`
  ADD PRIMARY KEY (`id_num_serie`),
  ADD KEY `id_produit` (`id_produit`),
  ADD KEY `id_status_numserie` (`id_status_numserie`);

--
-- Index pour la table `produits`
--
ALTER TABLE `produits`
  ADD PRIMARY KEY (`id_produit`),
  ADD KEY `id_status_produit` (`id_status_produit`);

--
-- Index pour la table `proprietes_num_serie`
--
ALTER TABLE `proprietes_num_serie`
  ADD PRIMARY KEY (`id_prop`),
  ADD KEY `id_num_serie` (`id_num_serie`);

--
-- Index pour la table `status_num_serie`
--
ALTER TABLE `status_num_serie`
  ADD PRIMARY KEY (`id_status_num_serie`);

--
-- Index pour la table `status_produit`
--
ALTER TABLE `status_produit`
  ADD PRIMARY KEY (`id_status_produit`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `identifiant` (`identifiant`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `lien_num_serie`
--
ALTER TABLE `lien_num_serie`
  ADD CONSTRAINT `lien_num_serie_ibfk_1` FOREIGN KEY (`id_num_serie_parent`) REFERENCES `num_series` (`id_num_serie`),
  ADD CONSTRAINT `lien_num_serie_ibfk_2` FOREIGN KEY (`id_num_serie_enfant`) REFERENCES `num_series` (`id_num_serie`);

--
-- Contraintes pour la table `mouvement`
--
ALTER TABLE `mouvement`
  ADD CONSTRAINT `mouvement_ibfk_1` FOREIGN KEY (`id_num_serie`) REFERENCES `num_series` (`id_num_serie`);

--
-- Contraintes pour la table `num_series`
--
ALTER TABLE `num_series`
  ADD CONSTRAINT `num_series_ibfk_1` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`),
  ADD CONSTRAINT `num_series_ibfk_2` FOREIGN KEY (`id_produit`) REFERENCES `produits` (`id_produit`),
  ADD CONSTRAINT `num_series_ibfk_3` FOREIGN KEY (`id_status_numserie`) REFERENCES `status_num_serie` (`id_status_num_serie`);

--
-- Contraintes pour la table `produits`
--
ALTER TABLE `produits`
  ADD CONSTRAINT `produits_ibfk_1` FOREIGN KEY (`id_status_produit`) REFERENCES `status_produit` (`id_status_produit`);

--
-- Contraintes pour la table `proprietes_num_serie`
--
ALTER TABLE `proprietes_num_serie`
  ADD CONSTRAINT `proprietes_num_serie_ibfk_1` FOREIGN KEY (`id_num_serie`) REFERENCES `num_series` (`id_num_serie`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
